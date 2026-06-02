@extends('layouts.landing')

@section('title', 'Circular Journal | RÉUTILISER')

@section('content')
<main class="max-w-[1600px] mx-auto px-8 md:px-16 py-24">
    <div class="mb-32 reveal-item text-center">
        <p class="font-label-caps text-secondary mb-8 tracking-[0.6em] text-sm opacity-60">STORIES & PROCESS</p>
        <h1 class="font-display-lg text-primary leading-tight">The Circular Journal</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-24">
        <!-- Featured Post -->
        <article class="md:col-span-12 group reveal-item mb-24">
            <a href="{{ url('/journal/' . $posts[0]['slug']) }}" class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="aspect-video bg-secondary-container rounded-[3rem] overflow-hidden shadow-2xl">
                    <img src="{{ $posts[0]['image'] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-1000" alt="Featured">
                </div>
                <div class="space-y-8">
                    <span class="font-label-caps text-[12px] text-primary border border-primary px-5 py-2 rounded-full tracking-widest">FEATURED STORY</span>
                    <h2 class="font-display-lg text-5xl md:text-7xl text-primary leading-tight group-hover:underline underline-offset-8 decoration-1">{{ $posts[0]['title'] }}</h2>
                    <p class="font-body-lg text-secondary text-2xl leading-relaxed">{{ $posts[0]['excerpt'] }}</p>
                    <p class="font-label-caps text-sm text-secondary tracking-widest opacity-60">{{ $posts[0]['date'] }} — BY RÉUTILISER TEAM</p>
                </div>
            </a>
        </article>

        <!-- Post Grid -->
        <div class="md:col-span-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 md:gap-24">
            @foreach($posts as $post)
            @if(!$loop->first)
            <article class="group reveal-item">
                <a href="{{ url('/journal/' . $post['slug']) }}" class="space-y-8">
                    <div class="aspect-[4/5] bg-secondary-container rounded-[2.5rem] overflow-hidden shadow-xl">
                        <img src="{{ $post['image'] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-1000" alt="{{ $post['title'] }}">
                    </div>
                    <div class="space-y-6">
                        <p class="font-label-caps text-[11px] text-secondary tracking-[0.3em]">{{ $post['date'] }}</p>
                        <h3 class="font-headline-md text-3xl text-primary leading-tight group-hover:underline underline-offset-4">{{ $post['title'] }}</h3>
                        <p class="font-body-md text-secondary line-clamp-2 text-lg">{{ $post['excerpt'] }}</p>
                    </div>
                </a>
            </article>
            @endif
            @endforeach
        </div>
    </div>

    <!-- Newsletter -->
    <section class="mt-48 py-32 md:py-48 bg-surface-container-low rounded-[4rem] reveal-item text-center">
        <div class="max-w-3xl mx-auto px-8 space-y-12">
            <h2 class="font-display-lg text-5xl text-primary leading-tight">Deepen your connection to circular luxury</h2>
            <p class="font-body-lg text-secondary text-2xl">Sign up for our monthly digest featuring artisan spotlights and archival drops.</p>
            <div class="flex border-b-2 border-primary max-w-lg mx-auto pb-4">
                <input class="bg-transparent border-none w-full focus:ring-0 text-center font-label-caps text-lg text-primary placeholder:opacity-20" placeholder="YOUR EMAIL ADDRESS" type="email"/>
                <button class="material-symbols-outlined text-primary text-3xl">east</button>
            </div>
        </div>
    </section>
</main>
@endsection
