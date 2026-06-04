@extends('layouts.landing')

@section('title', 'Your Collection | RÉUTILISER')

@section('content')

<main class="max-w-[1440px] mx-auto px-8 md:px-16 py-12">
    <!-- Page Header -->
    <div class="mb-20 reveal-item text-center md:text-left">
        <h1 class="font-display-lg text-primary mb-4 uppercase tracking-tighter">Your Collection</h1>
        <p class="font-body-md text-secondary max-w-lg mx-auto md:mx-0 text-lg opacity-60">Review your archival selections before they continue their journey with you.</p>
    </div>

    @if($cart->items->isEmpty())
        <div class="text-center py-32 reveal-item">
            <span class="material-symbols-outlined text-6xl text-secondary opacity-20 mb-8">shopping_bag</span>
            <p class="font-body-lg text-secondary italic mb-12">Your archive is currently empty.</p>
            <a href="{{ route('shop') }}"
               class="inline-block bg-primary text-white px-12 py-6 rounded-full font-label-caps text-[11px] tracking-widest hover:bg-primary-container transition-all shadow-xl uppercase">
                Explore Archives
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-20 items-start">
            <!-- Left Column: Items List -->
            <div class="lg:col-span-8 space-y-12">
                @foreach($cart->items as $item)
                    <div class="flex flex-col md:flex-row gap-8 pb-12 border-b border-primary/5 group reveal-item">
                        <!-- Product Image -->
                        <div class="w-full md:w-48 h-64 bg-secondary-container rounded-3xl overflow-hidden flex-shrink-0 shadow-sm">
                            <img src="{{ $item->product?->primary_image_url ?? asset('images/placeholder.webp') }}"
                                 alt="{{ $item->product?->name ?? 'Product' }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        </div>

                        <!-- Product Info -->
                        <div class="flex-grow flex flex-col justify-between py-2">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-headline-md text-3xl text-primary font-bold mb-2">{{ $item->product?->name ?? 'Unknown Product' }}</h3>
                                        <p class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-60">
                                            @if($item->variant)
                                                {{ $item->variant->size }} / {{ $item->variant->color }}
                                            @else
                                                Standard Edition
                                            @endif
                                        </p>
                                    </div>
                                    <p class="font-headline-md text-2xl text-primary">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <p class="font-body-md text-secondary text-sm leading-relaxed max-w-md opacity-80">
                                    {{ $item->product?->short_description ?? 'An archival piece reconstructed for a circular future.' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center justify-between mt-8 gap-6">
                                <div class="flex items-center gap-6 bg-surface-container-low p-2 rounded-2xl">
                                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-4">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button"
                                                @click="(function(){let i=$el.parentElement.querySelector('input[name=quantity]'); if(parseInt(i.value) > 1) { i.value=parseInt(i.value)-1; i.form.submit(); }})()"
                                                class="w-10 h-10 flex items-center justify-center text-primary hover:bg-primary hover:text-white rounded-xl transition-all font-bold">
                                            <span class="material-symbols-outlined text-sm">remove</span>
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10"
                                               class="w-8 text-center bg-transparent border-none font-body-md text-primary font-bold focus:ring-0 p-0"
                                               @change="$el.form.submit()">
                                        <button type="button"
                                                @click="(function(){let i=$el.parentElement.querySelector('input[name=quantity]'); if(parseInt(i.value) < 10) { i.value=parseInt(i.value)+1; i.form.submit(); }})()"
                                                class="w-10 h-10 flex items-center justify-center text-primary hover:bg-primary hover:text-white rounded-xl transition-all font-bold">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                        </button>
                                    </form>
                                </div>

                                <form method="POST" action="{{ route('cart.remove', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-label-caps text-[10px] text-red-400 hover:text-red-600 tracking-widest uppercase transition-colors flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                        Remove from Collection
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right Column: Summary -->
            <aside class="lg:col-span-4 bg-white p-10 md:p-16 rounded-[3rem] shadow-2xl reveal-item lg:sticky lg:top-32">
                <h2 class="font-headline-md text-3xl text-primary font-bold mb-12 border-b border-primary/5 pb-6">Summary</h2>
                
                <div class="space-y-6 mb-12">
                    <div class="flex justify-between items-center">
                        <span class="font-label-caps text-[12px] text-secondary tracking-widest uppercase opacity-40">Subtotal</span>
                        <span class="font-body-md text-primary font-bold text-xl">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-[10px] text-secondary tracking-widest leading-loose opacity-40 uppercase">
                        Shipping and taxes calculated at checkout.
                    </p>
                </div>

                <div class="space-y-6">
                    <a href="{{ route('checkout.index') }}"
                       class="block w-full text-center bg-primary text-white py-8 rounded-full font-label-caps tracking-[0.3em] text-[11px] font-bold hover:bg-primary-container transition-all shadow-2xl uppercase">
                        Secure Checkout
                    </a>
                    <a href="{{ route('shop') }}"
                       class="block w-full text-center py-6 rounded-full font-label-caps tracking-[0.2em] text-[10px] text-secondary border border-primary/10 hover:bg-primary/5 transition-all uppercase">
                        Continue Browsing
                    </a>
                </div>

                <div class="mt-16 flex justify-center gap-10 opacity-30">
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="material-symbols-outlined text-lg">shield</span>
                        <span class="text-[8px] uppercase tracking-widest">Secure</span>
                    </div>
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="material-symbols-outlined text-lg">eco</span>
                        <span class="text-[8px] uppercase tracking-widest">Circular</span>
                    </div>
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="material-symbols-outlined text-lg">local_shipping</span>
                        <span class="text-[8px] uppercase tracking-widest">Tracked</span>
                    </div>
                </div>
            </aside>
        </div>
    @endif
</main>
@endsection
