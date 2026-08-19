import './bootstrap';
import { createApp, h } from 'vue';
import CountUp from './components/CountUp.vue';
import GallerySlider from './components/GallerySlider.vue';

// ---------- Header / layout interactions (progressive enhancement) ----------
const header = document.querySelector('.site-header');
const onScroll = () => {
    if (header) {
        document.body.classList.toggle('header-scrolled', window.scrollY > 8);
    }
};
onScroll();
window.addEventListener('scroll', onScroll, { passive: true });

// Mobile menu
const mobileMenu = document.getElementById('mobileMenu');
const mobileMenuBtn = document.getElementById('mobileMenuBtn');

const toggleMobileMenu = (force) => {
    if (!mobileMenu) return;
    const open = force ?? !mobileMenu.dataset.open;
    mobileMenu.dataset.open = open ? '1' : '';
    const panel = mobileMenu.querySelector('div.absolute.right-0');
    const overlay = mobileMenu.querySelector('div.absolute.inset-0');
    const iconOpen = document.getElementById('iconOpen');
    const iconClose = document.getElementById('iconClose');

    if (open) {
        mobileMenu.setAttribute('aria-hidden', 'false');
        mobileMenuBtn?.setAttribute('aria-expanded', 'true');
        panel.classList.remove('-translate-x-full');
        overlay.classList.remove('opacity-0');
        iconOpen?.classList.add('hidden');
        iconClose?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        mobileMenu.setAttribute('aria-hidden', 'true');
        mobileMenuBtn?.setAttribute('aria-expanded', 'false');
        panel.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0');
        iconOpen?.classList.remove('hidden');
        iconClose?.classList.add('hidden');
        document.body.style.overflow = '';
    }
};

mobileMenuBtn?.addEventListener('click', () => toggleMobileMenu());
mobileMenu?.querySelectorAll('[data-mobile-close]').forEach((el) =>
    el.addEventListener('click', () => toggleMobileMenu(false))
);

// Search overlay
const searchOverlay = document.getElementById('searchOverlay');
const searchToggle = document.getElementById('searchToggle');
const openSearch = () => {
    if (!searchOverlay) return;
    searchOverlay.classList.remove('invisible', 'opacity-0');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('searchInput')?.focus(), 150);
};
const closeSearch = () => {
    if (!searchOverlay) return;
    searchOverlay.classList.add('invisible', 'opacity-0');
    document.body.style.overflow = '';
};
searchToggle?.addEventListener('click', openSearch);
searchOverlay?.addEventListener('mousedown', (e) => {
    if (e.target === searchOverlay) closeSearch();
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeSearch();
        toggleMobileMenu(false);
    }
});

// Account dropdown
const accountBtn = document.querySelector('[data-account-toggle]');
const accountDropdown = document.querySelector('[data-account-dropdown]');
if (accountBtn && accountDropdown) {
    accountBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const open = accountDropdown.classList.toggle('visible');
        accountDropdown.classList.toggle('invisible', !open);
        accountDropdown.classList.toggle('opacity-0', !open);
        accountDropdown.classList.toggle('scale-95', !open);
    });
    document.addEventListener('click', (e) => {
        if (!accountDropdown.contains(e.target) && !accountBtn.contains(e.target)) {
            accountDropdown.classList.remove('visible');
            accountDropdown.classList.add('invisible', 'opacity-0', 'scale-95');
        }
    });
}

// Book Now smooth scroll to a booking entry point on non-list pages
const bookNowLink = document.getElementById('bookNowLink');
if (bookNowLink) {
    bookNowLink.addEventListener('click', (e) => {
        const tours = document.getElementById('tours');
        if (tours && !bookNowLink.getAttribute('href')) {
            e.preventDefault();
            tours.scrollIntoView({ behavior: 'smooth' });
        }
    });
}

// ---------- Scroll reveal (vanilla, preserves server-rendered content) ----------
// NOTE: Reveal must NOT be mounted with Vue because app.mount() replaces the
// element's content — wiping the SSR markup. We only toggle a class instead.
const revealEls = document.querySelectorAll('[data-vue="Reveal"]');
revealEls.forEach((el) => {
    const delay = el.dataset.props ? (JSON.parse(el.dataset.props).delay || 0) : 0;
    el.style.setProperty('--reveal-delay', `${delay}ms`);
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    el.classList.add('is-revealed');
                    io.unobserve(el);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );
    io.observe(el);
});

// ---------- Vue component mounting ----------
// Only islands that RENDER their own content are mounted here (CountUp,
// GallerySlider). Reveal is handled above with vanilla JS because app.mount()
// would wipe its server-rendered content.
const components = { CountUp, GallerySlider };

document.querySelectorAll('[data-vue]').forEach((el) => {
    const name = el.dataset.vue;
    if (!components[name]) return;

    const props = el.dataset.props ? JSON.parse(el.dataset.props) : {};
    const Cmp = components[name];

    const app = createApp({
        render: () => h(Cmp, props),
    });
    app.mount(el);
});