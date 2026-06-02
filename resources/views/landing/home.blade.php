@extends('layouts.landing')

@section('title', 'RÉUTILISER | Conscious Luxury for a Circular Future')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[90vh] flex flex-col justify-center px-12 md:px-24 overflow-hidden border border-primary bg-surface mx-4 md:mx-12 rounded-3xl reveal-item">
    <div class="absolute inset-0 z-0 opacity-40">
        <img alt="Hero" class="w-full h-full object-cover grayscale parallax-img" src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1200&auto=format&fit=crop"/>
    </div>
    <div class="relative z-10 max-w-5xl">
        <p class="font-label-caps text-primary tracking-[0.5em] mb-8 uppercase text-[12px] opacity-70">ESTABLISHED 2024 — PARIS ARCHIVES</p>
        <h1 class="font-display-lg text-primary leading-[0.9] mb-10 text-6xl md:text-[100px]">
            Circular Luxury<br/>
            <span class="italic text-secondary font-light">Reconstructed.</span>
        </h1>
        <div class="flex flex-wrap gap-8 items-center mt-16">
            <a href="{{ url('/shop') }}" class="bg-primary text-on-primary px-16 py-6 font-label-caps tracking-widest hover:bg-primary-container transition-all rounded-full border border-primary shadow-2xl">EXPLORE ARCHIVES</a>
            <div class="h-px w-24 bg-primary/20 hidden md:block"></div>
            <a href="{{ url('/lookbook') }}" class="font-label-caps text-primary tracking-widest hover:italic transition-all">VIEW LOOKBOOK</a>
        </div>
    </div>
</section>

<!-- Vision & Impact -->
<section class="py-48 px-8 md:px-24 border-0">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-24 items-center">
        <div class="lg:col-span-6 space-y-12 reveal-item">
            <h2 class="font-headline-lg text-5xl md:text-7xl text-primary leading-tight">Our Radical<br/>Commitment.</h2>
            <div class="space-y-8 border-l-2 border-primary pl-10 py-4">
                <p class="font-body-lg text-secondary text-2xl leading-relaxed italic">
                    "We believe the most sustainable garment is the one that already exists, reimagined into a singular artifact."
                </p>
            </div>
            <p class="font-body-md text-secondary max-w-lg leading-loose">
                Réutiliser transforms archival industrial textiles into one-of-a-kind luxury items through a proprietary reconstruction process developed in our Paris atelier.
            </p>
            <div class="pt-8">
                <a href="{{ url('/about') }}" class="inline-block border border-primary px-12 py-4 rounded-full font-label-caps text-[11px] text-primary hover:bg-primary hover:text-white transition-all shadow-lg">READ THE MANIFESTO</a>
            </div>
        </div>
        <div class="lg:col-span-6 relative group reveal-item">
            <div class="border border-primary p-6 rounded-[3rem] overflow-hidden bg-white shadow-2xl">
                <img alt="Vision" class="w-full aspect-[4/5] object-cover group-hover:scale-105 transition-transform duration-[3000ms]" src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=800&auto=format&fit=crop"/>
            </div>
            <div class="absolute -bottom-12 -right-8 bg-primary text-white p-12 rounded-[2rem] shadow-2xl hidden md:block">
                <p class="font-display-lg text-6xl leading-none mb-2">12+</p>
                <p class="font-label-caps text-[10px] tracking-widest opacity-70">ARTISAN ATELIERS</p>
            </div>
        </div>
    </div>
</section>

<!-- Curated Drop -->
<section class="py-48 bg-surface-container-low border-y border-primary/10 reveal-item">
    <div class="max-w-[1600px] mx-auto px-8 md:px-24">
        <div class="flex flex-col md:flex-row justify-between items-end mb-32 border-b border-primary/20 pb-16">
            <div>
                <p class="font-label-caps text-primary tracking-[0.4em] mb-4 text-[10px]">COLLECTION 001</p>
                <h2 class="font-headline-lg text-6xl text-primary">Curated Drops</h2>
            </div>
            <a class="font-label-caps text-primary border-b border-primary pb-2 hover:tracking-[0.2em] transition-all mt-8 md:mt-0" href="{{ url('/shop') }}">VIEW ALL UNIQUE PIECES</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-20">
            @foreach($featuredProducts as $product)
            <div class="group reveal-item">
                <div class="product-card p-4 mb-10 relative overflow-hidden">
                    <div class="aspect-[3/4] rounded-[1.2rem] overflow-hidden">
                        <img src="{{ $product['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2000ms]" alt="{{ $product['name'] }}">
                    </div>
                    @if($product['tag'])
                    <span class="absolute top-10 right-10 bg-white border border-primary px-5 py-2 rounded-full font-label-caps text-[9px] text-primary shadow-xl">{{ $product['tag'] }}</span>
                    @endif
                    <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                         <a href="{{ url('/product/' . $product['id']) }}" class="bg-white text-primary px-10 py-4 rounded-full font-label-caps text-[10px] tracking-widest shadow-2xl border border-primary">VIEW PIECE</a>
                    </div>
                </div>
                <div class="px-2 space-y-4">
                    <div class="flex justify-between items-start">
                        <h3 class="font-body-lg text-2xl text-primary font-bold">{{ $product['name'] }}</h3>
                        <span class="font-headline-md text-2xl text-primary">${{ number_format($product['price'], 0) }}</span>
                    </div>
                    <p class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-60">{{ $product['material'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Journal Ticker -->
<div class="py-12 bg-primary overflow-hidden border-0">
    <div class="flex gap-24 animate-[scroll_40s_linear_infinite] whitespace-nowrap text-white font-display-lg text-4xl opacity-80 italic">
        @for($i=0; $i<10; $i++)
        <span>RADICAL TRANSPARENCY • CIRCULAR DESIGN • ARCHIVAL RECONSTRUCTION • LOCAL ARTISANS •</span>
        @endfor
    </div>
</div>

<style>
@keyframes scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
</style>

<!-- Journal Preview -->
<section class="py-48 px-8 md:px-24 reveal-item">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-24">
        <div class="lg:col-span-4 space-y-10">
            <p class="font-label-caps text-primary tracking-[0.4em] text-[10px]">STORIES</p>
            <h2 class="font-headline-lg text-5xl md:text-7xl text-primary leading-tight">The Circular Journal</h2>
            <p class="font-body-md text-secondary leading-loose max-w-sm">Deep dives into our manufacturing process, artisan profiles, and the philosophy of reuse.</p>
            <div class="pt-8">
                <a href="{{ url('/journal') }}" class="font-label-caps text-primary border-b-2 border-primary pb-2 hover:tracking-widest transition-all">READ ALL STORIES</a>
            </div>
        </div>
        <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-16">
            @foreach($journalPosts as $post)
            <article class="group reveal-item">
                <div class="aspect-square overflow-hidden rounded-[2.5rem] border border-primary/20 mb-10 relative shadow-2xl">
                    <img class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-[1500ms]" src="{{ $post['image'] }}" alt="{{ $post['title'] }}"/>
                    <div class="absolute bottom-8 left-8 bg-white border border-primary px-6 py-2 rounded-full font-label-caps text-[9px] text-primary">{{ $post['category'] }}</div>
                </div>
                <p class="font-label-caps text-[10px] text-secondary mb-4 tracking-[0.3em]">{{ $post['date'] }}</p>
                <h3 class="font-headline-md text-3xl text-primary mb-6 leading-tight group-hover:underline decoration-1">{{ $post['title'] }}</h3>
                <a href="{{ url('/journal/' . $post['slug']) }}" class="font-label-caps text-[11px] text-secondary inline-flex items-center gap-4 hover:text-primary transition-all">
                    READ STORY <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
