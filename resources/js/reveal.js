/**
 * Scroll-reveal for public pages — progressive enhancement.
 *
 * Elements opt in with two inert attributes:
 *   data-reveal        → the element fades + rises in when scrolled into view
 *   data-reveal-group  → its direct children reveal with a staggered delay
 *
 * Safety: the hiding CSS is armed only by the `reveal-enabled` class, which we
 * add here at runtime and ONLY when IntersectionObserver is available and the
 * user hasn't asked to reduce motion. If this script never runs (JS off/blocked/
 * failed) the class is absent and all content renders fully visible.
 */

const STAGGER_MS = 80;
const STAGGER_CAP_MS = 480;

const enabled =
    'IntersectionObserver' in window &&
    !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Arm the hide CSS as early as the bundle executes (minimises any flash), and
// only when we can actually animate. Tied to the bundle: if JS never runs, the
// class is absent and nothing is hidden.
if (enabled) {
    document.documentElement.classList.add('reveal-enabled');
}

function reveal(el) {
    el.classList.add('is-visible');
    // Drop the per-item stagger delay once revealed so hover transitions stay snappy.
    el.addEventListener(
        'transitionend',
        () => {
            el.style.transitionDelay = '';
        },
        { once: true },
    );
}

function init() {
    if (!enabled) {
        return;
    }

    try {
        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    const el = entry.target;

                    if (el.hasAttribute('data-reveal-group')) {
                        Array.from(el.children).forEach((child, i) => {
                            child.style.transitionDelay = Math.min(i * STAGGER_MS, STAGGER_CAP_MS) + 'ms';
                            reveal(child);
                        });
                    } else {
                        reveal(el);
                    }

                    obs.unobserve(el); // reveal once; don't re-hide on scroll-up
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -8% 0px' },
        );

        document.querySelectorAll('[data-reveal], [data-reveal-group]').forEach((el) => observer.observe(el));
    } catch (e) {
        // Any failure → make sure nothing is left hidden.
        document.documentElement.classList.remove('reveal-enabled');
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
