<nav class="fixed top-0 left-0 right-0 z-40 bg-surface border-b border-outline-variant">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
        <a href="/" class="font-headline text-xl text-primary tracking-tight">
            RÉUTILISER
        </a>

        <div class="flex items-center gap-6">
            <a href="{{ route('shop') }}" class="font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors">
                Produk
            </a>

            @auth
                <a href="{{ route('cart.index') }}" class="relative text-on-surface-variant hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    @php
                        $cartCount = 0;
                        if (auth()->check() && auth()->user()->cart) {
                            $cartCount = auth()->user()->cart->total_items;
                        }
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-error text-on-primary text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-body-md text-body-md">
                        {{ auth()->user()->name }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-surface shadow-lg border border-outline-variant py-1" x-cloak>
                        <a href="{{ route('account.orders.index') }}" class="block px-4 py-2 text-sm font-body-md text-on-surface-variant hover:bg-surface-variant transition-colors">
                            Pesanan Saya
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm font-body-md text-on-surface-variant hover:bg-surface-variant transition-colors">
                                Dashboard Admin
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm font-body-md text-error hover:bg-surface-variant transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-primary text-on-primary px-4 py-2 font-label-caps text-label-caps hover:opacity-90 transition-opacity">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</nav>
