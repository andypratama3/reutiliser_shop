@extends('layouts.landing')

@section('title', 'Page Not Found | RÉUTILISER')

@section('content')
<main class="min-h-[60vh] flex flex-col items-center justify-center text-center px-margin-mobile">
    <div class="reveal-item">
        <h1 class="font-display-lg text-[120px] text-primary leading-none opacity-10">404</h1>
        <div class="-mt-16">
            <p class="font-label-caps text-primary tracking-[0.4em] mb-6">LOST IN THE ARCHIVES</p>
            <h2 class="font-headline-lg text-headline-lg text-primary mb-12">The page you seek has been repurposed.</h2>
            <div class="flex flex-col md:flex-row gap-6 justify-center">
                <a href="{{ url('/shop') }}" class="bg-primary text-on-primary px-12 py-5 font-label-caps text-label-caps hover:bg-primary-container transition-all">SHOP NEW ARRIVALS</a>
                <a href="{{ url('/') }}" class="border border-primary text-primary px-12 py-5 font-label-caps text-label-caps hover:bg-primary hover:text-white transition-all">RETURN TO SANCTUARY</a>
            </div>
        </div>
    </div>
</main>
@endsection
