@extends('layouts.landing')

@section('title', 'About RÉUTILISER | Conscious Luxury')

@section('content')
<main class="max-w-[1600px] mx-auto px-8 md:px-16">
    <!-- Hero Section -->
    <section class="py-24 md:py-48 flex flex-col lg:flex-row items-center gap-24">
        <div class="lg:w-1/2 reveal-item">
            <p class="font-label-caps text-primary tracking-[0.4em] mb-8 text-sm opacity-60">THE MANIFESTO</p>
            <h1 class="font-display-lg text-primary leading-[0.9] mb-12">Archival Reconstruction.<br/><span class="italic text-secondary font-light">Radical Circularity.</span></h1>
            <p class="font-body-lg text-secondary leading-relaxed max-w-xl mb-16 text-2xl">
                Réutiliser merupakan hasil kolaborasi antara sebuah brand fashion dengan designer lokal yang kembali mengolah limbah industri fashion menjadi fashion item limited yang bernilai jual tinggi.
            </p>
            <div class="flex items-center gap-8">
                <div class="h-px w-20 bg-primary/20"></div>
                <a href="#" class="font-label-caps text-primary tracking-widest hover:italic transition-all">ESTABLISHED 2024 / PARIS</a>
            </div>
        </div>
        <div class="lg:w-1/2 reveal-item">
            <div class="relative aspect-square max-w-xl mx-auto flex items-center justify-center">
                <div class="absolute inset-0 border-2 border-dashed border-primary/10 rounded-full animate-[spin_60s_linear_infinite]"></div>
                <div class="absolute inset-12 rounded-full flex items-center justify-center p-16">
                    {{-- make background blend --}}
                    <img src="{{ asset('assets_landing/logo_core.png') }}" class="w-full h-full object-cover" style="background-blend-mode: multiply !important;" alt="Logo">
                </div>
            </div>
        </div>
    </section>

    <!-- Strategy Pillars -->
    <section class="py-32 md:py-48 bg-surface-container-low rounded-[3rem] px-8 md:px-24 mb-32 reveal-item">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16">
            @foreach(['Product' => 'Hand-sewn jackets and limited accessories.', 'Price' => 'Reflecting conscious craftsmanship.', 'Place' => 'Curated digital and physical pop-ups.', 'Promotion' => 'Storytelling from landfill to spotlight.'] as $title => $desc)
            <div class="space-y-6">
                <span class="font-label-caps text-primary/40 text-[10px] tracking-widest">0{{ $loop->iteration }}</span>
                <h3 class="font-headline-md text-3xl text-primary">{{ $title }}</h3>
                <p class="font-body-md text-secondary leading-relaxed text-lg">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- The Collective -->
    <section class="py-32 md:py-48 reveal-item">
        <div class="flex justify-between items-end mb-32 border-b border-primary/10 pb-16">
            <h2 class="font-display-lg text-6xl text-primary">The Collective</h2>
            <p class="font-label-caps text-secondary tracking-widest hidden md:block">MEET THE ARTISANS</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 md:gap-24">
            @foreach($teamMembers as $member)
            <div class="reveal-item group">
                <div class="aspect-[3/4] rounded-[2.5rem] overflow-hidden mb-10 shadow-2xl">
                    <img src="{{ $member['image'] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000" alt="{{ $member['name'] }}">
                </div>
                <p class="font-label-caps text-primary tracking-widest text-[11px] mb-4">{{ $member['role'] }}</p>
                <h3 class="font-headline-md text-4xl text-primary">{{ $member['name'] }}</h3>
            </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
