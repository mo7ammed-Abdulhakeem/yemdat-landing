/**
 * Yemdat service worker — installable PWA + automatic offline.
 *
 * Strategy summary:
 *   - HTML navigations ............ NetworkFirst  (fresh online, last-seen page offline, else offline.html)
 *   - /build/* hashed assets ...... CacheFirst    (immutable, safe to cache forever)
 *   - images (/storage, /icons) ... CacheFirst    (bounded by an LRU cap)
 *   - Bunny CDN fonts ............. StaleWhileRevalidate
 *   - auth / personalized paths ... NetworkOnly    (never cached — see NETWORK_ONLY_PREFIXES)
 *   - any non-GET request ......... NetworkOnly
 *
 * Bump CACHE_VERSION on every release so old caches are purged on activate.
 */

const CACHE_VERSION = 'yemdat-v5.7.1';

const PRECACHE = `${CACHE_VERSION}-precache`;
const PAGE_CACHE = `${CACHE_VERSION}-pages`;
const ASSET_CACHE = `${CACHE_VERSION}-assets`;
const IMAGE_CACHE = `${CACHE_VERSION}-images`;

const OFFLINE_URL = '/offline.html';
const IMAGE_CACHE_LIMIT = 60;

// Stable-URL shell pieces worth precaching. '/' is intentionally NOT precached —
// it is dynamic/locale-aware HTML and is cached at runtime on first visit instead.
const PRECACHE_URLS = [
    OFFLINE_URL,
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

// Personalized, one-time, or redirect routes that must always hit the network
// and must never be served from cache. Matched by path prefix.
const NETWORK_ONLY_PREFIXES = [
    '/admin',
    '/livewire',
    '/my-profile',
    '/login',
    '/logout',
    '/register',
    '/forgot-password',
    '/claim-profile',
    '/verify-email',
    '/verify/',
    '/track/',
    '/unsubscribe',
    '/resubscribe',
    '/lang/',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(PRECACHE)
            .then((cache) => cache.addAll(PRECACHE_URLS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((names) => Promise.all(
                names
                    .filter((name) => !name.startsWith(CACHE_VERSION))
                    .map((name) => caches.delete(name))
            ))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const req = event.request;

    // Only GET is cacheable; everything else (form POSTs etc.) goes straight to network.
    if (req.method !== 'GET') return;

    const url = new URL(req.url);

    // Cross-origin requests.
    if (url.origin !== self.location.origin) {
        if (url.hostname === 'fonts.bunny.net') {
            event.respondWith(staleWhileRevalidate(req, ASSET_CACHE));
        }
        return; // all other cross-origin requests: leave to the network
    }

    // Same-origin personalized / one-time routes: never intercept.
    if (NETWORK_ONLY_PREFIXES.some((prefix) => url.pathname.startsWith(prefix))) {
        return;
    }

    // Immutable hashed build assets.
    if (url.pathname.startsWith('/build/')) {
        event.respondWith(cacheFirst(req, ASSET_CACHE));
        return;
    }

    // HTML navigations.
    if (req.mode === 'navigate') {
        event.respondWith(networkFirst(req, PAGE_CACHE));
        return;
    }

    // Images (uploaded media + app icons).
    if (req.destination === 'image' || url.pathname.startsWith('/storage/') || url.pathname.startsWith('/icons/')) {
        event.respondWith(cacheFirst(req, IMAGE_CACHE, IMAGE_CACHE_LIMIT));
        return;
    }

    // Other same-origin GETs (manifest, robots, misc static).
    event.respondWith(staleWhileRevalidate(req, ASSET_CACHE));
});

async function networkFirst(req, cacheName) {
    const cache = await caches.open(cacheName);
    try {
        const fresh = await fetch(req);
        // Cache only clean, same-origin 2xx responses (skips redirects / opaque).
        if (fresh && fresh.ok && fresh.type === 'basic') {
            cache.put(req, fresh.clone());
        }
        return fresh;
    } catch (e) {
        const cached = await cache.match(req);
        if (cached) return cached;
        const offline = await caches.match(OFFLINE_URL);
        return offline || new Response('Offline', { status: 503, statusText: 'Offline' });
    }
}

async function cacheFirst(req, cacheName, limit) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(req);
    if (cached) return cached;
    try {
        const fresh = await fetch(req);
        if (fresh && (fresh.ok || fresh.type === 'opaque')) {
            cache.put(req, fresh.clone());
            if (limit) trimCache(cacheName, limit);
        }
        return fresh;
    } catch (e) {
        return cached || Response.error();
    }
}

async function staleWhileRevalidate(req, cacheName) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(req);
    const network = fetch(req)
        .then((fresh) => {
            if (fresh && (fresh.ok || fresh.type === 'opaque')) {
                cache.put(req, fresh.clone());
            }
            return fresh;
        })
        .catch(() => cached);
    return cached || network;
}

// Simple FIFO eviction to keep a runtime cache bounded.
async function trimCache(cacheName, maxItems) {
    const cache = await caches.open(cacheName);
    const keys = await cache.keys();
    const overflow = keys.length - maxItems;
    for (let i = 0; i < overflow; i++) {
        await cache.delete(keys[i]);
    }
}
