/**
 * Custom "Install app" banner for the Yemdat PWA.
 *
 * - Chrome / Edge / Android: captures `beforeinstallprompt`, suppresses the
 *   default mini-infobar, and shows our own branded bottom banner. Tapping
 *   "Install" triggers the real browser install dialog.
 * - iPhone / iPad Safari: never fires `beforeinstallprompt`, so we show a
 *   manual "Share → Add to Home Screen" hint instead.
 * - Dismissible, and the dismissal is remembered for 14 days so it never nags.
 * - Never shown once the app is already installed (running standalone).
 *
 * Language + direction follow the page (<html lang> / <html dir>), so it is
 * automatically bilingual EN/AR + RTL.
 */

const DISMISS_KEY = 'yemdat_pwa_dismissed';
const DISMISS_DAYS = 14;

const T = {
    en: {
        title: 'Install Yemdat',
        body: 'Quick access and offline reading — add it to your home screen.',
        bodyIos: 'Tap the Share button, then choose “Add to Home Screen”.',
        install: 'Install',
        dismiss: 'Dismiss',
    },
    ar: {
        title: 'ثبّت يمدات',
        body: 'وصول سريع وقراءة دون اتصال — أضِفه إلى شاشتك الرئيسية.',
        bodyIos: 'اضغط زر المشاركة، ثم اختر «إضافة إلى الشاشة الرئيسية».',
        install: 'تثبيت',
        dismiss: 'إغلاق',
    },
};

let deferredPrompt = null;

function isStandalone() {
    return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
}

function isIos() {
    return /iphone|ipad|ipod/i.test(window.navigator.userAgent) && !window.MSStream;
}

function dismissedRecently() {
    try {
        const ts = parseInt(localStorage.getItem(DISMISS_KEY) || '0', 10);
        return ts && (Date.now() - ts) < DISMISS_DAYS * 86400000;
    } catch (e) {
        return false;
    }
}

function rememberDismissal() {
    try { localStorage.setItem(DISMISS_KEY, String(Date.now())); } catch (e) {}
}

function strings() {
    return document.documentElement.lang.startsWith('ar') ? T.ar : T.en;
}

function injectStyles() {
    if (document.getElementById('yemdat-pwa-styles')) return;
    const css = `
        .yemdat-pwa-banner {
            position: fixed; left: 50%; bottom: 16px; transform: translateX(-50%) translateY(160%);
            z-index: 9999; width: calc(100% - 24px); max-width: 460px;
            background: #FFFFFF; color: #593E2D; border: 1px solid rgba(89,62,45,.12);
            border-radius: 16px; box-shadow: 0 12px 32px rgba(62,43,32,.18);
            padding: 14px 16px; display: flex; align-items: center; gap: 14px;
            font-family: 'Instrument Sans', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            transition: transform .35s cubic-bezier(.16,1,.3,1); box-sizing: border-box;
        }
        html[dir="rtl"] .yemdat-pwa-banner {
            font-family: 'Tajawal', system-ui, -apple-system, 'Segoe UI', Tahoma, sans-serif;
        }
        .yemdat-pwa-banner.is-visible { transform: translateX(-50%) translateY(0); }
        .yemdat-pwa-icon { flex: 0 0 auto; width: 44px; height: 44px; }
        .yemdat-pwa-text { flex: 1 1 auto; min-width: 0; }
        .yemdat-pwa-text strong { display: block; font-size: .98rem; font-weight: 700; color: #3E2B20; }
        .yemdat-pwa-text span { display: block; font-size: .82rem; line-height: 1.4; color: #6b5444; margin-top: 2px; }
        .yemdat-pwa-actions { flex: 0 0 auto; display: flex; align-items: center; gap: 8px; }
        .yemdat-pwa-install {
            background: #593E2D; color: #F2CB57; border: none; cursor: pointer;
            padding: 9px 16px; border-radius: 10px; font-weight: 600; font-size: .9rem;
            font-family: inherit; white-space: nowrap; transition: opacity .15s ease;
        }
        .yemdat-pwa-install:hover { opacity: .9; }
        .yemdat-pwa-close {
            background: transparent; border: none; cursor: pointer; color: #9a8775;
            width: 30px; height: 30px; border-radius: 8px; font-size: 20px; line-height: 1;
            display: flex; align-items: center; justify-content: center;
        }
        .yemdat-pwa-close:hover { background: rgba(89,62,45,.06); color: #593E2D; }
        @media (prefers-reduced-motion: reduce) { .yemdat-pwa-banner { transition: none; } }
    `;
    const style = document.createElement('style');
    style.id = 'yemdat-pwa-styles';
    style.textContent = css;
    document.head.appendChild(style);
}

function buildBanner(mode) {
    const s = strings();
    const banner = document.createElement('div');
    banner.className = 'yemdat-pwa-banner';
    banner.setAttribute('role', 'dialog');
    banner.setAttribute('aria-label', s.title);

    const logo = `
        <svg class="yemdat-pwa-icon" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="10" y="5" width="30" height="8" rx="2" fill="#F2CB57"/>
            <rect x="5" y="16" width="35" height="8" rx="2" fill="#C88D16"/>
            <rect x="0" y="27" width="40" height="8" rx="2" fill="#593E2D"/>
        </svg>`;

    const body = mode === 'ios' ? s.bodyIos : s.body;
    const action = mode === 'ios'
        ? ''
        : `<button type="button" class="yemdat-pwa-install">${s.install}</button>`;

    banner.innerHTML = `
        ${logo}
        <div class="yemdat-pwa-text">
            <strong>${s.title}</strong>
            <span>${body}</span>
        </div>
        <div class="yemdat-pwa-actions">
            ${action}
            <button type="button" class="yemdat-pwa-close" aria-label="${s.dismiss}">&times;</button>
        </div>`;

    document.body.appendChild(banner);
    requestAnimationFrame(() => banner.classList.add('is-visible'));
    return banner;
}

function hideBanner(banner) {
    if (!banner) return;
    banner.classList.remove('is-visible');
    setTimeout(() => banner.remove(), 400);
}

function showBanner(mode) {
    if (document.querySelector('.yemdat-pwa-banner')) return;
    injectStyles();
    const banner = buildBanner(mode);

    const closeBtn = banner.querySelector('.yemdat-pwa-close');
    closeBtn.addEventListener('click', () => {
        rememberDismissal();
        hideBanner(banner);
    });

    const installBtn = banner.querySelector('.yemdat-pwa-install');
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            hideBanner(banner);
            deferredPrompt.prompt();
            try { await deferredPrompt.userChoice; } catch (e) {}
            deferredPrompt = null;
        });
    }
}

export function initInstallBanner() {
    if (isStandalone() || dismissedRecently()) return;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        showBanner('install');
    });

    window.addEventListener('appinstalled', () => {
        deferredPrompt = null;
        rememberDismissal();
        const banner = document.querySelector('.yemdat-pwa-banner');
        if (banner) hideBanner(banner);
    });

    // iOS Safari can't fire beforeinstallprompt — offer the manual hint instead.
    if (isIos()) {
        setTimeout(() => {
            if (!dismissedRecently() && !isStandalone()) showBanner('ios');
        }, 2500);
    }
}
