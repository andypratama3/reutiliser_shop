@extends('layouts.landing')

@section('title', 'Lookbooks | RÉUTILISER')

@section('content')
<main class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-12">
    <div class="mb-24 reveal-item text-center">
        <p class="font-label-caps text-secondary mb-4 tracking-[0.4em]">VISUAL ARCHIVES</p>
        <h1 class="font-display-lg text-headline-lg md:text-display-lg text-primary leading-tight">Seasonal Lookbooks</h1>
    </div>

    <div class="space-y-48">
        @foreach($collections as $collection)
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center reveal-item">
            <div class="{{ $loop->index % 2 == 0 ? 'lg:col-span-7' : 'lg:col-span-7 lg:order-2' }} aspect-[16/9] bg-secondary-container overflow-hidden group">
                <img src="{{ $collection['image'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[2000ms]" alt="{{ $collection['name'] }}">
            </div>
            <div class="{{ $loop->index % 2 == 0 ? 'lg:col-span-5' : 'lg:col-span-5 lg:order-1' }} space-y-8">
                <div>
                    <p class="font-label-caps text-primary tracking-widest">{{ $collection['year'] }} COLLECTION</p>
                    <h2 class="font-display-lg text-4xl md:text-6xl text-primary mt-4">{{ $collection['name'] }}</h2>
                </div>
                <p class="font-body-lg text-secondary leading-relaxed">
                    A narrative of circularity told through the lens of high-end tailoring. Each piece in this collection is a singular artifact of our radical zero-waste mission.
                </p>
                <div class="flex gap-8">
                    <button class="font-label-caps text-primary border-b border-primary pb-1 hover:opacity-60 transition-opacity">VIEW GALLERY</button>
                    <a href="{{ url('/shop') }}" class="font-label-caps text-secondary hover:text-primary transition-colors">SHOP THE PIECES</a>
                </div>
            </div>
        </section>
        @endforeach
    </div>
</main>
@endsection
