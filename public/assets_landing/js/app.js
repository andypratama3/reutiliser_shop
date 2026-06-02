
document.addEventListener('DOMContentLoaded', () => {
    // Reveal Animations (Intersection Observer)
    const revealOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => { 
            if (entry.isIntersecting) {
                entry.target.classList.add('active'); 
            }
        });
    }, revealOptions);
    document.querySelectorAll('.reveal-item').forEach(el => revealObserver.observe(el));

    // Cart Drawer Logic
    const sideNav = document.getElementById('side-nav');
    const cartToggle = document.getElementById('cart-toggle');
    const cartClose = document.getElementById('cart-close');

    if (cartToggle && sideNav && cartClose) {
        cartToggle.addEventListener('click', (e) => {
            e.preventDefault();
            sideNav.classList.remove('translate-x-full');
        });
        cartClose.addEventListener('click', () => {
            sideNav.classList.add('translate-x-full');
        });
    }

    // Mobile Menu Fix & Logic
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuClose = document.getElementById('mobile-menu-close');

    if (mobileMenuToggle && mobileMenu && mobileMenuClose) {
        mobileMenuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            mobileMenu.classList.remove('translate-x-full');
        });
        mobileMenuClose.addEventListener('click', () => {
            mobileMenu.classList.add('translate-x-full');
        });
    }

    // Search Overlay Logic
    const searchOverlay = document.getElementById('search-overlay');
    const searchToggle = document.getElementById('search-toggle');
    const searchClose = document.getElementById('search-close');

    if (searchToggle && searchOverlay && searchClose) {
        searchToggle.addEventListener('click', () => {
            searchOverlay.classList.remove('translate-y-full');
        });
        searchClose.addEventListener('click', () => {
            searchOverlay.classList.add('translate-y-full');
        });
    }

    // Magnetic Buttons Interaction
    const magneticBtns = document.querySelectorAll('.magnetic-btn');
    magneticBtns.forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translate(0, 0)';
        });
    });

    // Parallax Scrolling for Lookbook
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        document.querySelectorAll('.parallax-img').forEach(img => {
            const speed = img.dataset.speed || 0.1;
            img.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });

    // Quantity Adjuster
    document.querySelectorAll('.qty-adjust').forEach(adj => {
        const minus = adj.querySelector('.qty-minus');
        const plus = adj.querySelector('.qty-plus');
        const input = adj.querySelector('input');
        minus?.addEventListener('click', () => { if (input.value > 1) input.value--; });
        plus?.addEventListener('click', () => { input.value++; });
    });
});
