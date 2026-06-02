<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'RÉUTILISER | Conscious Luxury for a Circular Future')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Caslon+Text:ital,wght@0,400;0,700;1,400&family=Hanken+Grotesk:wght@300;400;600;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#2a4a38",
                        "background": "#fcf9f8",
                        "surface": "#fcf9f8",
                        "outline-variant": "#c1c8c1",
                        "secondary": "#605e59",
                        "surface-container-low": "#f6f3f2",
                        "surface-container-highest": "#e5e2e1",
                    },
                    "borderRadius": {
                        "DEFAULT": "16px",
                        "lg": "24px",
                        "xl": "32px",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline-lg": ["Libre Caslon Text"],
                        "body-lg": ["Hanken Grotesk"],
                        "body-md": ["Hanken Grotesk"],
                        "label-caps": ["Hanken Grotesk"],
                        "headline-md": ["Libre Caslon Text"],
                        "display-lg": ["Libre Caslon Text"]
                    }
                },
            },
        }
    </script>
    <style>
        /* Modern Minimalist - Zero Visible Borders */
        * { border-width: 0 !important; }
        
        .reveal-item { opacity: 0; transform: translateY(20px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-item.active { opacity: 1; transform: translateY(0); }
        
        /* Soft Radius Utility */
        img { border-radius: 24px !important; }
        button, .btn { border-radius: 9999px !important; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        input, select, textarea { border-radius: 16px !important; background: #f0eded; padding: 1.25rem 2rem; }
        
        .product-card, .sidebar-card, aside { border-radius: 32px !important; overflow: hidden; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(42, 74, 56, 0.08); }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #fcf9f8; }
        ::-webkit-scrollbar-thumb { background: #2a4a38; border-radius: 10px; }

        /* Typography Enlargement */
        .nav-link { font-size: 14px !important; letter-spacing: 0.3em !important; font-weight: 700 !important; }
        h1, .font-display-lg { font-size: 4rem !important; line-height: 1 !important; }
        @media (min-width: 1024px) {
            h1, .font-display-lg { font-size: 7rem !important; }
            h2, .font-headline-lg { font-size: 4.5rem !important; line-height: 1.1 !important; }
        }
        p, .font-body-md { font-size: 1.1rem !important; line-height: 1.6 !important; }
        .font-body-lg { font-size: 1.3rem !important; }
        
        .header-scrolled { box-shadow: 0 10px 30px rgba(0,0,0,0.03); padding-top: 1rem !important; padding-bottom: 1rem !important; }
    </style>
    @stack('css')
</head>
<body class="bg-background text-on-background selection:bg-primary selection:text-on-primary font-body-md overflow-x-hidden">
    <!-- TopNavBar -->
    <header id="main-header" class="fixed top-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-xl transition-all duration-500 py-6 md:py-10">
        <div class="flex justify-between items-center w-full px-8 md:px-16 max-w-[1600px] mx-auto">
            <div class="flex gap-20 items-center">
                <a class="font-headline-md text-4xl md:text-5xl tracking-tighter text-primary" href="{{ url('/') }}">RÉUTILISER</a>
                <nav class="hidden lg:flex gap-12">
                    <a class="nav-link font-label-caps {{ Request::is('about') ? 'text-primary' : 'text-secondary opacity-60 hover:opacity-100' }} transition-all" href="{{ url('/about') }}">ABOUT</a>
                    <a class="nav-link font-label-caps {{ Request::is('shop*') ? 'text-primary' : 'text-secondary opacity-60 hover:opacity-100' }} transition-all" href="{{ url('/shop') }}">SHOP</a>
                    <a class="nav-link font-label-caps {{ Request::is('journal*') ? 'text-primary' : 'text-secondary opacity-60 hover:opacity-100' }} transition-all" href="{{ url('/journal') }}">JOURNAL</a>
                    <a class="nav-link font-label-caps {{ Request::is('contact') ? 'text-primary' : 'text-secondary opacity-60 hover:opacity-100' }} transition-all" href="{{ url('/contact') }}">CONTACT</a>
                </nav>
            </div>
            <div class="flex items-center gap-10">
                <button class="p-2 text-primary hover:scale-110 transition-transform"><span class="material-symbols-outlined text-3xl">search</span></button>
                <button class="p-2 text-primary relative hover:scale-110 transition-transform" id="cart-toggle">
                    <span class="material-symbols-outlined text-3xl">shopping_bag</span>
                    <span class="absolute -top-1 -right-1 bg-primary text-white text-[10px] w-6 h-6 flex items-center justify-center rounded-full shadow-lg">1</span>
                </button>
                <button class="lg:hidden text-primary" id="mobile-menu-toggle"><span class="material-symbols-outlined text-4xl">menu</span></button>
            </div>
        </div>
    </header>

    <!-- Side Cart -->
    <aside class="fixed right-0 top-0 h-full w-full md:w-[500px] z-[120] bg-surface translate-x-full transition-transform duration-700 shadow-2xl flex flex-col" id="side-nav">
        <div class="p-12 flex flex-col h-full">
            <div class="flex justify-between items-center mb-16">
                <h2 class="font-headline-md text-4xl text-primary font-bold">Your Collection</h2>
                <button class="material-symbols-outlined text-secondary text-4xl hover:rotate-90 transition-transform" id="cart-close">close</button>
            </div>
            <div class="flex-grow space-y-10 overflow-y-auto">
                <div class="flex gap-8 p-6 bg-surface-container-low rounded-3xl">
                    <div class="w-24 h-32 bg-secondary-container rounded-xl overflow-hidden">
                        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1551028719-00167b16eac5?q=80&w=400&auto=format&fit=crop" alt="Item"/>
                    </div>
                    <div>
                        <p class="font-body-md text-primary font-bold text-2xl">Patchwork Jacket</p>
                        <p class="font-label-caps text-sm text-secondary tracking-widest mt-2">$385.00</p>
                    </div>
                </div>
            </div>
            <div class="mt-auto pt-12">
                <a href="{{ url('/checkout') }}" class="block w-full text-center bg-primary text-on-primary py-8 rounded-3xl font-label-caps tracking-[0.2em] text-lg hover:bg-primary-container transition-all shadow-2xl">CHECKOUT NOW</a>
            </div>
        </div>
    </aside>

    <!-- Mobile Menu -->
    <div class="fixed inset-0 bg-primary z-[130] translate-x-full transition-transform duration-700 flex flex-col justify-center items-center gap-16 text-white" id="mobile-menu">
        <button class="absolute top-12 right-12 text-white" id="mobile-menu-close"><span class="material-symbols-outlined text-6xl">close</span></button>
        @foreach(['Home' => '/', 'About' => '/about', 'Shop' => '/shop', 'Journal' => '/journal'] as $label => $link)
        <a class="font-display-lg text-6xl hover:italic transition-all" href="{{ url($link) }}">{{ $label }}</a>
        @endforeach
    </div>

    <main class="pt-32 md:pt-48">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-highest mt-48 py-32">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-20 px-8 md:px-16 max-w-[1600px] mx-auto text-center md:text-left">
            <div class="space-y-10">
                <a class="font-headline-md text-5xl tracking-tighter text-primary" href="#">RÉUTILISER</a>
                <p class="font-body-md text-secondary leading-relaxed italic text-xl">Crafting a circular future through radical transparency.</p>
            </div>
            <div>
                <h4 class="font-label-caps text-primary font-bold text-lg tracking-widest mb-12">EXPLORE</h4>
                <ul class="space-y-6">
                    <li><a class="text-secondary hover:text-primary transition-all text-lg" href="{{ url('/shop') }}">Shop Archives</a></li>
                    <li><a class="text-secondary hover:text-primary transition-all text-lg" href="{{ url('/journal') }}">Circular Journal</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-caps text-primary font-bold text-lg tracking-widest mb-12">SUPPORT</h4>
                <ul class="space-y-6">
                    <li><a class="text-secondary hover:text-primary transition-all text-lg" href="{{ url('/contact') }}">Concierge</a></li>
                    <li><a class="text-secondary hover:text-primary transition-all text-lg" href="{{ url('/faq') }}">FAQ Center</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-caps text-primary font-bold text-lg tracking-widest mb-12">COLLECTIVE</h4>
                <div class="flex bg-white rounded-2xl p-3 shadow-sm border-0">
                    <input class="bg-transparent border-none w-full focus:ring-0 text-lg text-primary placeholder:text-secondary/40 px-6" placeholder="EMAIL ADDRESS" type="email"/>
                    <button class="bg-primary text-white w-16 h-16 flex items-center justify-center rounded-2xl"><span class="material-symbols-outlined text-2xl">east</span></button>
                </div>
            </div>
        </div>
        <div class="max-w-[1600px] mx-auto px-16 mt-32 pt-12 border-t border-primary/5 text-center md:flex md:justify-between items-center opacity-30">
            <p class="font-label-caps text-sm tracking-[0.4em]">© 2024 RÉUTILISER. ALL RIGHTS RESERVED.</p>
            <p class="font-label-caps text-sm tracking-[0.4em] mt-4 md:mt-0 uppercase">Conscious Luxury for a Circular Future</p>
        </div>
    </footer>

    <script src="{{ asset('assets_landing/js/app.js') }}"></script>
    <script>
        const header = document.getElementById('main-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                header.classList.add('header-scrolled');
                header.style.paddingTop = '1rem';
                header.style.paddingBottom = '1rem';
            } else {
                header.classList.remove('header-scrolled');
                header.style.paddingTop = '';
                header.style.paddingBottom = '';
            }
        });
    </script>
    @stack('js')
</body>
</html>
