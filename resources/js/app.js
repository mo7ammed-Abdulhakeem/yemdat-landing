import './bootstrap';
import './reveal';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Register the PWA service worker (production builds only — avoids clashing with Vite HMR in dev).
if ('serviceWorker' in navigator && !import.meta.env.DEV) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js').catch(() => {});
    });
}
