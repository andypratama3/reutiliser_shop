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
    <link rel="stylesheet" href="{{ asset('assets_landing/css/style.css') }}">
    <style>
        /* Modern Minimalist - Zero Visible Borders */
        * { border-width: 0 !important; }
        
        .reveal-item { opacity: 0; transform: translateY(20px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-item.active { opacity: 1; transform: translateY(0); }
        
        /* Soft Radius Utility */
        img { border-radius: 20px !important; }
        button, .btn { border-radius: 9999px !important; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        input, select, textarea { border-radius: 12px !important; background: #f0eded; padding: 1rem 1.5rem; }
        
        .product-card, .sidebar-card, aside { border-radius: 24px !important; overflow: hidden; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 15px 30px rgba(42, 74, 56, 0.06); }

        /* Typography Scaling */
        .nav-link { font-size: 13px !important; letter-spacing: 0.25em !important; font-weight: 600 !important; }
        h1, .font-display-lg { font-size: 3rem !important; line-height: 1.1 !important; }
        @media (min-width: 1024px) {
            h1, .font-display-lg { font-size: 5rem !important; }
            h2, .font-headline-lg { font-size: 3.5rem !important; line-height: 1.2 !important; }
        }
        p, .font-body-md { font-size: 1rem !important; line-height: 1.6 !important; }
        .font-body-lg { font-size: 1.15rem !important; }
        
        .header-scrolled { box-shadow: 0 10px 30px rgba(0,0,0,0.02); padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #fcf9f8; }
        ::-webkit-scrollbar-thumb { background: #2a4a38; border-radius: 10px; }
    </style>
    @stack('css')
</head>
<body class="bg-background text-on-background selection:bg-primary selection:text-on-primary font-body-md overflow-x-hidden">
    <!-- TopNavBar -->
    <header id="main-header" class="fixed top-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-xl transition-all duration-500 py-5 md:py-8">
        <div class="flex justify-between items-center w-full px-8 md:px-16 max-w-[1440px] mx-auto">
            <div class="flex gap-16 items-center">
                <a class="font-headline-md text-3xl md:text-4xl tracking-tighter text-primary" href="{{ url('/') }}">RÉUTILISER</a>
                <nav class="hidden lg:flex gap-10">
                    @foreach(['ABOUT' => '/about', 'SHOP' => '/shop', 'JOURNAL' => '/journal', 'CONTACT' => '/contact'] as $label => $link)
                    <a class="nav-link font-label-caps {{ Request::is(trim($link, '/').'*') ? 'text-primary' : 'text-secondary opacity-60 hover:opacity-100' }} transition-all" href="{{ url($link) }}">{{ $label }}</a>
                    @endforeach
                </nav>
            </div>
            <div class="flex items-center gap-8">
                <button class="p-2 text-primary hover:scale-110 transition-transform" id="search-toggle"><span class="material-symbols-outlined text-2xl">search</span></button>
                <a href="{{ url('/wishlist') }}" class="p-2 text-primary hover:scale-110 transition-transform"><span class="material-symbols-outlined text-2xl">favorite</span></a>
                <button class="p-2 text-primary relative hover:scale-110 transition-transform" id="cart-toggle">
                    <span class="material-symbols-outlined text-2xl">shopping_bag</span>
                    <span class="absolute top-1 right-1 bg-primary text-white text-[9px] w-5 h-5 flex items-center justify-center rounded-full shadow-lg border border-white">{{ $landingCart->total_items }}</span>
                </button>
                <!-- HAMBURGER: hidden lg:flex ensures it only appears on screens smaller than 1024px -->
                <button class="lg:hidden text-primary p-2 flex items-center justify-center" id="mobile-menu-toggle">
                    <span class="material-symbols-outlined text-3xl">menu</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Side Cart -->
    <aside class="fixed right-0 top-0 h-full w-full md:w-[450px] z-[120] bg-surface translate-x-full transition-transform duration-700 shadow-2xl flex flex-col" id="side-nav">
        <div class="p-8 md:p-12 flex flex-col h-full overflow-y-auto">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="font-headline-md text-3xl text-primary font-bold">Collection</h2>
                    <p class="font-label-caps text-[11px] text-secondary tracking-widest mt-1">{{ $landingCart->total_items }} ARCHIVAL PIECE(S) RESERVED</p>
                </div>
                <button class="material-symbols-outlined text-secondary hover:rotate-90 transition-transform text-3xl" id="cart-close">close</button>
            </div>
            <div class="flex-grow space-y-10">
                @forelse($landingCart->items as $item)
                <div class="flex gap-8 group">
                    <div class="w-24 h-32 bg-secondary-container rounded-xl overflow-hidden shadow-sm flex-shrink-0">
                        <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" src="{{ $item->product->primaryImage?->path ?? 'https://placehold.co/200x200/e2e8f0/64748b?text=Item' }}" alt="{{ $item->product->name }}"/>
                    </div>
                    <div class="flex-grow flex flex-col py-2">
                        <div class="flex justify-between items-start">
                            <h3 class="font-body-md text-primary font-bold text-xl leading-tight">{{ $item->product->name }}</h3>
                            <span class="font-body-md text-primary font-bold text-lg">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                        <p class="font-label-caps text-[10px] text-secondary tracking-widest mt-2 uppercase">
                            @if($item->variant)
                                {{ $item->variant->size }} / {{ $item->variant->color }}
                            @else
                                Qty: {{ $item->quantity }}
                            @endif
                        </p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="font-label-caps text-[10px] text-secondary">Qty: {{ $item->quantity }}</span>
                            <form method="POST" action="{{ route('cart.remove', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="font-label-caps text-[10px] text-red-400 hover:text-red-600 transition-colors">REMOVE</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-16">
                    <span class="material-symbols-outlined text-5xl text-secondary opacity-30 mb-4">shopping_bag</span>
                    <p class="font-body-md text-secondary opacity-60 italic">Your archive is empty.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-auto pt-12 border-t">
                <div class="flex justify-between items-center mb-6 px-2">
                    <span class="font-headline-md text-xl text-primary">Subtotal</span>
                    <span class="font-headline-md text-xl text-primary">Rp {{ number_format($landingCart->subtotal, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="block w-full text-center bg-primary text-white py-6 rounded-3xl font-label-caps tracking-[0.2em] text-sm hover:bg-primary-container transition-all shadow-2xl">CHECKOUT NOW</a>
            </div>
        </div>
    </aside>

    <!-- Mobile Menu Overlay -->
    <div class="fixed inset-0 bg-primary z-[130] translate-x-full transition-transform duration-700 flex flex-col justify-center items-center gap-10 text-white" id="mobile-menu">
        <button class="absolute top-10 right-10 text-white" id="mobile-menu-close"><span class="material-symbols-outlined text-5xl">close</span></button>
        @foreach(['Home' => '/', 'About' => '/about', 'Shop' => '/shop', 'Journal' => '/journal', 'Contact' => '/contact'] as $label => $link)
        <a class="font-display-lg text-5xl hover:italic transition-all" href="{{ url($link) }}">{{ $label }}</a>
        @endforeach
    </div>

    <main class="pt-28 md:pt-36">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-highest mt-32 py-24">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-16 px-8 md:px-16 max-w-[1440px] mx-auto text-center md:text-left">
            <div class="space-y-8">
                <a class="font-headline-md text-4xl tracking-tighter text-primary" href="#">RÉUTILISER</a>
                <p class="font-body-md text-secondary leading-relaxed italic text-lg">Crafting a circular future through radical transparency.</p>
            </div>
            <div>
                <h4 class="font-label-caps text-primary font-bold text-sm tracking-widest mb-10 uppercase">Explore</h4>
                <ul class="space-y-4">
                    <li><a class="text-secondary hover:text-primary transition-all text-sm" href="{{ url('/shop') }}">Shop Archives</a></li>
                    <li><a class="text-secondary hover:text-primary transition-all text-sm" href="{{ url('/journal') }}">Journal</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-caps text-primary font-bold text-sm tracking-widest mb-10 uppercase">Support</h4>
                <ul class="space-y-4">
                    <li><a class="text-secondary hover:text-primary transition-all text-sm" href="{{ url('/contact') }}">Concierge</a></li>
                    <li><a class="text-secondary hover:text-primary transition-all text-sm" href="{{ url('/faq') }}">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-caps text-primary font-bold text-sm tracking-widest mb-10 uppercase">Collective</h4>
                <div class="flex bg-white rounded-2xl p-2 shadow-sm">
                    <input class="bg-transparent border-none w-full focus:ring-0 text-sm text-primary placeholder:text-secondary/40 px-4" placeholder="EMAIL" type="email"/>
                    <button class="bg-primary text-white w-10 h-10 flex items-center justify-center rounded-xl"><span class="material-symbols-outlined text-sm">east</span></button>
                </div>
            </div>
        </div>
        <div class="max-w-[1440px] mx-auto px-16 mt-20 pt-10 border-t border-primary/5 text-center opacity-30">
            <p class="font-label-caps text-[9px] tracking-[0.4em]">© 2024 RÉUTILISER. ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

    <script src="{{ asset('assets_landing/js/app.js') }}"></script>
    @stack('js')
</body>
</html>
