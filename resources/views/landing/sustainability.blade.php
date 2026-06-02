@extends('layouts.landing')

@section('title', 'Sustainability | RÉUTILISER')

@section('content')
<main class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-12">
    <div class="max-w-4xl mx-auto text-center mb-32 reveal-item">
        <p class="font-label-caps text-secondary mb-6 tracking-[0.4em]">OUR COMMITMENT</p>
        <h1 class="font-display-lg text-headline-lg md:text-display-lg text-primary leading-tight mb-8">Toward a Radical Circular Future</h1>
        <p class="font-body-lg text-secondary leading-relaxed">We believe that the future of luxury is not in production, but in the intelligent, creative rebirth of what already exists. Our methodology combines high-end tailoring with zero-waste radicalism.</p>
    </div>

    <!-- The Pillars -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-16 mb-48">
        <div class="reveal-item text-center">
            <span class="material-symbols-outlined text-primary text-5xl mb-8">recycling</span>
            <h3 class="font-headline-md text-headline-md text-primary mb-6">Zero New Material</h3>
            <p class="font-body-md text-secondary leading-relaxed">We source 100% of our textiles from archival stock, vintage industrial collections, and discarded luxury remnants.</p>
        </div>
        <div class="reveal-item text-center">
            <span class="material-symbols-outlined text-primary text-5xl mb-8">engineering</span>
            <h3 class="font-headline-md text-headline-md text-primary mb-6">Artisan Provenance</h3>
            <p class="font-body-md text-secondary leading-relaxed">Each piece is hand-sewn in localized artisan workshops, reducing carbon footprint and preserving heritage crafts.</p>
        </div>
        <div class="reveal-item text-center">
            <span class="material-symbols-outlined text-primary text-5xl mb-8">history_edu</span>
            <h3 class="font-headline-md text-headline-md text-primary mb-6">Full Transparency</h3>
            <p class="font-body-md text-secondary leading-relaxed">From the original factory code to the artisan's signature, every garment's lifecycle is documented and verifiable.</p>
        </div>
    </div>

    <!-- Impact Map Simulation -->
    <div class="bg-primary text-on-primary p-12 md:p-24 reveal-item">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
            <div class="space-y-12">
                <h2 class="font-headline-lg text-headline-lg leading-tight">The 2024 Impact Dashboard</h2>
                <div class="space-y-8">
                    <div class="reveal-item">
                        <div class="flex justify-between font-label-caps text-[10px] mb-2">
                            <span>TEXTILE WASTE DIVERTED</span>
                            <span>2,410 KG / 5,000 KG GOAL</span>
                        </div>
                        <div class="h-1 bg-white/20">
                            <div class="h-full bg-white w-[48%]"></div>
                        </div>
                    </div>
                    <div class="reveal-item">
                        <div class="flex justify-between font-label-caps text-[10px] mb-2">
                            <span>ARTISAN LABOR HOURS</span>
                            <span>12,800 HOURS</span>
                        </div>
                        <div class="h-1 bg-white/20">
                            <div class="h-full bg-white w-[72%]"></div>
                        </div>
                    </div>
                </div>
                <button class="border border-white px-12 py-5 font-label-caps text-label-caps hover:bg-white hover:text-primary transition-all">DOWNLOAD FULL REPORT (PDF)</button>
            </div>
            <div class="aspect-square bg-white/5 stitch-border flex items-center justify-center p-12">
                <div class="text-center">
                    <p class="font-display-lg text-[120px] leading-none mb-4">42%</p>
                    <p class="font-label-caps text-[12px] tracking-[0.3em]">REDUCTION IN WATER IMPACT<br/>COMPARED TO NEW DENIM</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Final Call to Action -->
    <div class="mt-48 text-center reveal-item">
        <h2 class="font-headline-lg text-headline-lg text-primary mb-12">Support the Circular Movement</h2>
        <div class="flex justify-center gap-8">
            <a href="{{ url('/shop') }}" class="bg-primary text-on-primary px-12 py-5 font-label-caps text-label-caps hover:bg-primary-container transition-all">SHOP ARCHIVES</a>
            <a href="{{ url('/journal') }}" class="border border-primary text-primary px-12 py-5 font-label-caps text-label-caps hover:bg-primary hover:text-white transition-all">READ STORIES</a>
        </div>
    </div>
</main>
@endsection
