
document.addEventListener('DOMContentLoaded', () => {
    // Reveal Animations
    const observerOptions = { threshold: 0.1 };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { 
            if (entry.isIntersecting) {
                entry.target.classList.add('active'); 
            }
        });
    }, observerOptions);
    document.querySelectorAll('.reveal-item').forEach(el => observer.observe(el));

    // Cart Drawer Logic
    const sideNav = document.getElementById('side-nav');
    const cartToggle = document.getElementById('cart-toggle');
    const cartClose = document.getElementById('cart-close');

    if (cartToggle && sideNav && cartClose) {
        cartToggle.addEventListener('click', () => {
            sideNav.classList.remove('translate-x-full');
        });

        cartClose.addEventListener('click', () => {
            sideNav.classList.add('translate-x-full');
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

    // Mobile Menu Logic
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuClose = document.getElementById('mobile-menu-close');

    if (mobileMenuToggle && mobileMenu && mobileMenuClose) {
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.remove('translate-x-full');
        });
        mobileMenuClose.addEventListener('click', () => {
            mobileMenu.classList.add('translate-x-full');
        });
    }

    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Parallax Effect for Hero Images
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxImages = document.querySelectorAll('.parallax-img');
        parallaxImages.forEach(img => {
            img.style.transform = `translateY(${scrolled * 0.1}px)`;
        });
    });
});
