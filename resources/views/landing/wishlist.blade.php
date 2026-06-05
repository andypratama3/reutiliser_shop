@extends('layouts.landing')

@section('title', 'Your Wishlist | RÉUTILISER')

@section('content')
<main class="max-w-[1600px] mx-auto px-8 md:px-16 py-24">
    <div class="mb-32 reveal-item text-center">
        <p class="font-label-caps text-secondary mb-6 tracking-[0.4em] text-sm opacity-60">SAVED SELECTION</p>
        <h1 class="font-display-lg text-primary leading-tight">Your Wishlist</h1>
        <p class="font-body-md text-secondary mt-8 text-xl">Pieces you are watching for your future collection</p>
    </div>

    @if($wishlistItems->isEmpty())
    <section class="py-32 text-center reveal-item bg-surface-container-low rounded-[3rem]">
        <h2 class="font-headline-md text-4xl text-primary mb-12">Your wishlist is currently empty.</h2>
        <a href="{{ url('/shop') }}" class="bg-primary text-on-primary px-12 py-6 rounded-full font-label-caps tracking-widest hover:bg-primary-container transition-all shadow-xl">START EXPLORING</a>
    </section>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-24">
        @foreach($wishlistItems as $item)
        <div class="group reveal-item flex flex-col md:flex-row gap-12 items-center bg-white p-8 rounded-[3rem] shadow-xl">
            <div class="w-full md:w-1/3 aspect-[3/4] rounded-[2rem] overflow-hidden shadow-2xl">
                <img src="{{ $item['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="{{ $item['name'] }}">
            </div>
            <div class="w-full md:w-2/3 space-y-6">
                <p class="font-label-caps text-[10px] text-primary tracking-widest opacity-60 uppercase">{{ $item['category'] }}</p>
                <h3 class="font-headline-md text-4xl text-primary leading-tight">{{ $item['name'] }}</h3>
                <p class="font-headline-md text-2xl text-primary">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                <p class="font-body-md text-secondary italic">{{ $item['material'] }}</p>
                
                <div class="flex gap-4 pt-6">
                    <a href="{{ url('/product/' . $item['id']) }}" class="bg-primary text-on-primary px-8 py-4 rounded-full font-label-caps text-[11px] tracking-widest hover:bg-primary-container transition-all">VIEW PIECE</a>
                    <button class="border border-primary text-primary px-8 py-4 rounded-full font-label-caps text-[11px] tracking-widest hover:bg-primary hover:text-white transition-all">REMOVE</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</main>
@endsection
