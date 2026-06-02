@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8" x-data>
    <h1 class="font-headline text-3xl text-primary mb-8">Keranjang Belanja</h1>

    @if($cart->items->isEmpty())
        <div class="text-center py-20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-outline mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <p class="font-body-lg text-body-lg text-on-surface-variant mb-6">Keranjang belanja kamu masih kosong.</p>
            <a href="{{ route('products.index') }}"
               class="inline-block bg-primary text-on-primary px-8 py-3 font-label-caps text-label-caps tracking-wider hover:opacity-90 transition-opacity">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($cart->items as $item)
                <div class="flex gap-4 p-4 bg-surface border border-outline-variant">
                    <div class="w-20 h-24 bg-surface-container flex-shrink-0 overflow-hidden">
                        @if($item->product->primaryImage)
                            <img src="{{ Storage::url($item->product->primaryImage->path) }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="font-body-md text-body-md text-on-surface font-semibold truncate">{{ $item->product->name }}</h3>
                        @if($item->variant)
                            <p class="font-label-caps text-label-caps text-on-surface-variant mt-1">
                                {{ $item->variant->size }} / {{ $item->variant->color }}
                            </p>
                        @endif
                        <p class="font-semibold text-on-surface mt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>

                    <div class="flex flex-col items-end justify-between">
                        <form method="POST" action="{{ route('cart.remove', $item) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-on-surface-variant hover:text-error transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>

                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <button type="button"
                                        @click="(function(){let i=$el.parentElement.querySelector('input[name=quantity]');i.value=Math.max(1,parseInt(i.value)-1);i.form.submit()})()"
                                        class="w-8 h-8 border border-outline-variant flex items-center justify-center text-on-surface hover:bg-surface-variant transition-colors font-body-md">
                                    -
                                </button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10"
                                       class="w-12 text-center border border-outline-variant py-1 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary"
                                       @change="$el.form.submit()">
                                <button type="button"
                                        @click="(function(){let i=$el.parentElement.querySelector('input[name=quantity]');i.value=Math.min(10,parseInt(i.value)+1);i.form.submit()})()"
                                        class="w-8 h-8 border border-outline-variant flex items-center justify-center text-on-surface hover:bg-surface-variant transition-colors font-body-md">
                                    +
                                </button>
                            </form>
                        </div>

                        <p class="font-semibold text-on-surface">Rp {{ number_format($item->line_total, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 p-6 bg-surface border border-outline-variant">
            <div class="flex justify-between items-center mb-6">
                <span class="font-headline text-xl text-on-surface">Subtotal</span>
                <span class="font-headline text-xl text-on-surface">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout.index') }}"
               class="block w-full text-center bg-primary text-on-primary py-4 font-label-caps text-label-caps tracking-wider hover:opacity-90 transition-opacity">
                Lanjut ke Checkout
            </a>
            <a href="{{ route('products.index') }}"
               class="block w-full text-center mt-3 py-3 font-label-caps text-label-caps text-on-surface-variant border border-outline-variant hover:bg-surface-variant transition-colors">
                Lanjut Belanja
            </a>
        </div>
    @endif
</div>
@endsection
