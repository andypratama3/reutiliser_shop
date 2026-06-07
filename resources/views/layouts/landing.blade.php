<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('assets_landing/css/style.css') }}">
    <style>
        /* Modern Minimalist - Transition Utilities */
        .reveal-item { opacity: 0; transform: translateY(20px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-item.active { opacity: 1; transform: translateY(0); }
        
        /* Soft Radius & Interaction Utility */
        img:not(.logo) { border-radius: 24px; }
        button, .btn { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        
        input, select, textarea { 
            border-radius: 12px; 
            background: #f3f0ef; 
            border: 1px solid rgba(42, 74, 56, 0.1);
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            background: #fff !important;
            border-color: rgba(42, 74, 56, 0.2) !important;
            box-shadow: 0 0 0 4px rgba(42, 74, 56, 0.05) !important;
        }
        
        .product-card, .sidebar-card, aside { border-radius: 24px; overflow: hidden; background: #fff; }

        /* Typography Scaling */
        .nav-link { font-size: 11px; letter-spacing: 0.2em; font-weight: 700; text-transform: uppercase; }
        h1, .font-display-lg { font-size: 3.5rem; line-height: 1.05; font-weight: 700; }
        @media (min-width: 1024px) {
            h1, .font-display-lg { font-size: 5.5rem; }
            h2, .font-headline-lg { font-size: 4rem; line-height: 1.1; }
        }
        p, .font-body-md { font-size: 1rem; line-height: 1.7; }
        
        .header-scrolled { box-shadow: 0 10px 30px rgba(0,0,0,0.02); padding-top: 1rem !important; padding-bottom: 1rem !important; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #fcf9f8; }
        ::-webkit-scrollbar-thumb { background: #2a4a38; border-radius: 10px; }

        /* Fix for potential overlap */
        main { position: relative; z-index: 1; }
    </style>
    @stack('css')
</head>
<body class="bg-background text-on-background selection:bg-primary selection:text-on-primary font-body-md overflow-x-hidden">
    <!-- TopNavBar -->
    <header id="main-header" class="fixed top-0 left-0 right-0 z-[100] bg-surface/90 backdrop-blur-xl transition-all duration-500 py-6 md:py-10">
        <div class="flex justify-between items-center w-full px-8 md:px-16 max-w-[1440px] mx-auto">
            <div class="flex gap-20 items-center">
                <a class="logo font-headline-md text-3xl md:text-4xl tracking-tighter text-primary font-bold" href="{{ url('/') }}">RÉUTILISER</a>
                <nav class="hidden lg:flex gap-12">
                    @foreach(['ABOUT' => '/about', 'SHOP' => '/shop', 'JOURNAL' => '/journal', 'CONTACT' => '/contact'] as $label => $link)
                    <a class="nav-link {{ Request::is(trim($link, '/').'*') ? 'text-primary' : 'text-secondary opacity-50 hover:opacity-100 hover:text-primary' }} transition-all" href="{{ url($link) }}">{{ $label }}</a>
                    @endforeach
                </nav>
            </div>
            <div class="flex items-center gap-8">
                <button class="p-2 text-primary hover:scale-110 transition-transform" id="search-toggle"><span class="material-symbols-outlined text-2xl">search</span></button>
                <button class="p-2 text-primary relative hover:scale-110 transition-transform" id="cart-toggle">
                    <span class="material-symbols-outlined text-2xl">shopping_bag</span>
                    @if($landingCart->total_items > 0)
                        <span class="absolute top-0 right-0 bg-primary text-white text-[9px] w-5 h-5 flex items-center justify-center rounded-full shadow-lg border border-white">{{ $landingCart->total_items }}</span>
                    @endif
                </button>
                @auth
                <div class="relative group hidden lg:block">
                    <button class="p-2 text-primary hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl">person</span>
                    </button>
                    <div class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-primary/10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 py-4 z-50">
                        <p class="px-6 py-2 font-label-caps text-[9px] text-secondary tracking-widest uppercase opacity-40">{{ auth()->user()->name }}</p>
                        <a href="{{ route('account.orders.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm text-primary hover:bg-primary/5 transition-all font-bold tracking-wide">
                            <span class="material-symbols-outlined text-lg">receipt_long</span>
                            Pesanan Saya
                        </a>
                        <a href="{{ route('account.profile.edit') }}" class="flex items-center gap-3 px-6 py-3 text-sm text-primary hover:bg-primary/5 transition-all font-bold tracking-wide">
                            <span class="material-symbols-outlined text-lg">settings</span>
                            Profil
                        </a>
                        <a href="{{ route('account.alamat.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm text-primary hover:bg-primary/5 transition-all font-bold tracking-wide">
                            <span class="material-symbols-outlined text-lg">location_on</span>
                            Alamat
                        </a>
                        <div class="border-t border-primary/5 mt-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-6 py-3 text-sm text-red-600 hover:bg-red-50 transition-all font-bold tracking-wide w-full text-left">
                                    <span class="material-symbols-outlined text-lg">logout</span>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="p-2 text-primary hover:scale-110 transition-transform hidden lg:block">
                    <span class="material-symbols-outlined text-2xl">person</span>
                </a>
                @endauth
                <button class="lg:hidden text-primary p-2 flex items-center justify-center" id="mobile-menu-toggle">
                    <span class="material-symbols-outlined text-3xl">menu</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Side Cart -->
    <aside class="fixed right-0 top-0 h-full w-full md:w-[480px] z-[150] bg-surface translate-x-full transition-transform duration-700 shadow-2xl flex flex-col" id="side-nav">
        <div class="p-8 md:p-16 flex flex-col h-full overflow-y-auto">
            <div class="flex justify-between items-center mb-16">
                <div>
                    <h2 class="font-headline-md text-4xl text-primary font-bold">Archive</h2>
                    <p class="font-label-caps text-[10px] text-secondary tracking-[0.3em] mt-2 uppercase opacity-40">{{ $landingCart->total_items }} PIECES RESERVED</p>
                </div>
                <button class="material-symbols-outlined text-primary hover:rotate-90 transition-transform text-3xl" id="cart-close">close</button>
            </div>
            <div class="flex-grow space-y-12">
                @forelse($landingCart->items as $item)
                <div class="flex gap-8 group">
                    <div class="w-24 h-32 bg-secondary-container rounded-2xl overflow-hidden shadow-sm flex-shrink-0">
                        <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" src="{{ $item->product?->primary_image_url ?? asset('images/placeholder.webp') }}" alt="{{ $item->product?->name ?? 'Product' }}"/>
                    </div>
                    <div class="flex-grow flex flex-col justify-center py-2">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-body-md text-primary font-bold text-xl leading-tight">{{ $item->product?->name ?? 'Unknown Product' }}</h3>
                            <span class="font-body-md text-primary font-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                        <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40 mb-4">
                            @if($item->variant)
                                {{ $item->variant->size }} / {{ $item->variant->color }}
                            @else
                                Standard Edition
                            @endif
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="font-label-caps text-[10px] text-primary font-bold bg-primary/5 px-3 py-1 rounded-lg">QTY: {{ $item->quantity }}</span>
                            <form method="POST" action="{{ route('cart.remove', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="font-label-caps text-[9px] text-red-400 hover:text-red-600 tracking-widest uppercase transition-colors">REMOVE</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-24">
                    <span class="material-symbols-outlined text-7xl text-primary opacity-10 mb-6">shopping_bag</span>
                    <p class="font-body-md text-secondary opacity-60 italic text-lg">Your archive is currently empty.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-auto pt-16 border-t border-primary/5">
                <div class="flex justify-between items-center mb-8">
                    <span class="font-headline-md text-2xl text-primary font-bold">Subtotal</span>
                    <span class="font-headline-md text-2xl text-primary font-bold">Rp {{ number_format($landingCart->subtotal, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="block w-full text-center bg-primary text-white py-8 rounded-full font-label-caps tracking-[0.4em] text-[11px] font-bold hover:bg-primary-container transition-all shadow-2xl uppercase">Continue to Checkout</a>
            </div>
        </div>
    </aside>

    <!-- Mobile Menu Overlay -->
    <div class="fixed inset-0 bg-primary z-[200] translate-x-full transition-transform duration-700 flex flex-col justify-center items-center gap-10 text-white" id="mobile-menu">
        <button class="absolute top-10 right-10 text-white" id="mobile-menu-close"><span class="material-symbols-outlined text-5xl">close</span></button>
        @foreach(['Home' => '/', 'About' => '/about', 'Shop' => '/shop', 'Journal' => '/journal', 'Contact' => '/contact'] as $label => $link)
        <a class="font-display-lg text-6xl hover:italic transition-all uppercase tracking-tighter" href="{{ url($link) }}">{{ $label }}</a>
        @endforeach
        @auth
        <div class="border-t border-white/20 pt-8 mt-4 flex flex-col items-center gap-6">
            <a class="font-label-caps text-lg tracking-widest hover:italic transition-all" href="{{ route('account.orders.index') }}">Pesanan Saya</a>
            <a class="font-label-caps text-lg tracking-widest hover:italic transition-all" href="{{ route('account.profile.edit') }}">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="font-label-caps text-lg tracking-widest hover:italic transition-all text-red-300">Keluar</button>
            </form>
        </div>
        @else
        <div class="border-t border-white/20 pt-8 mt-4">
            <a class="font-label-caps text-lg tracking-widest hover:italic transition-all" href="{{ route('login') }}">Login</a>
        </div>
        @endauth
    </div>

    <main class="pt-36 md:pt-48">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-highest mt-40 py-32">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-20 px-8 md:px-16 max-w-[1440px] mx-auto text-center md:text-left">
            <div class="space-y-10">
                <a class="font-headline-md text-4xl tracking-tighter text-primary font-bold" href="#">RÉUTILISER</a>
                <p class="font-body-md text-secondary leading-relaxed italic text-lg opacity-60">Crafting a circular future through radical archival transparency.</p>
            </div>
            <div>
                <h4 class="font-label-caps text-primary font-bold text-[11px] tracking-[0.3em] mb-12 uppercase opacity-40">Navigation</h4>
                <ul class="space-y-6">
                    <li><a class="text-secondary hover:text-primary transition-all text-sm font-bold tracking-widest" href="{{ url('/shop') }}">SHOP ARCHIVES</a></li>
                    <li><a class="text-secondary hover:text-primary transition-all text-sm font-bold tracking-widest" href="{{ url('/journal') }}">JOURNAL</a></li>
                    <li><a class="text-secondary hover:text-primary transition-all text-sm font-bold tracking-widest" href="{{ url('/about') }}">OUR STORY</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-caps text-primary font-bold text-[11px] tracking-[0.3em] mb-12 uppercase opacity-40">Concierge</h4>
                <ul class="space-y-6">
                    <li><a class="text-secondary hover:text-primary transition-all text-sm font-bold tracking-widest" href="{{ url('/contact') }}">CLIENT SERVICE</a></li>
                    <li><a class="text-secondary hover:text-primary transition-all text-sm font-bold tracking-widest" href="{{ url('/faq') }}">SHIPPING & RETURNS</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-caps text-primary font-bold text-[11px] tracking-[0.3em] mb-12 uppercase opacity-40">Newsletter</h4>
                <div class="flex bg-white rounded-2xl p-2 shadow-sm border border-primary/5 focus-within:border-primary/20 transition-all">
                    <input class="bg-transparent border-none w-full focus:ring-0 text-sm text-primary placeholder:text-secondary/40 px-6 font-body-md" placeholder="JOIN OUR NEWSLETTER" type="email"/>
                    <button class="bg-primary text-white w-12 h-12 flex items-center justify-center rounded-xl hover:bg-primary-container transition-all"><span class="material-symbols-outlined text-sm">east</span></button>
                </div>
            </div>
        </div>
        <div class="max-w-[1440px] mx-auto px-16 mt-32 pt-12 border-t border-primary/5 text-center">
            <p class="font-label-caps text-[10px] tracking-[0.5em] text-secondary opacity-30">© 2026 RÉUTILISER. ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

    <script src="{{ asset('assets_landing/js/app.js') }}"></script>
    <script>
        function confirmDelete(event, message = 'Are you sure?') {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Confirmation',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2a4a38',
                cancelButtonColor: '#605e59',
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#2a4a38',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#2a4a38'
                });
            @endif
        });
    </script>
    @stack('js')
</body>
</html>
