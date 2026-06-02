@extends('layouts.landing')

@section('title', 'FAQ | RÉUTILISER Help Center')

@section('content')
<main class="max-w-3xl mx-auto px-margin-mobile md:px-margin-desktop py-24">
    <div class="text-center mb-24 reveal-item">
        <p class="font-label-caps text-secondary mb-4 tracking-widest">HELP CENTER</p>
        <h1 class="font-display-lg text-headline-lg text-primary">Frequently Asked Questions</h1>
    </div>

    <div class="space-y-12 reveal-item">
        @foreach($faqs as $faq)
        <div class="border-b border-outline-variant pb-12">
            <h3 class="font-headline-md text-2xl text-primary mb-6">{{ $faq['q'] }}</h3>
            <p class="font-body-lg text-secondary leading-relaxed">{{ $faq['a'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="mt-24 p-12 bg-surface-container-low text-center reveal-item">
        <h2 class="font-headline-md text-primary mb-6">Still have questions?</h2>
        <p class="font-body-md text-secondary mb-8">Our concierge team is available to assist you with any inquiries.</p>
        <a href="{{ url('/contact') }}" class="inline-block bg-primary text-on-primary px-12 py-5 font-label-caps text-label-caps hover:bg-primary-container transition-all">CONTACT CONCIERGE</a>
    </div>
</main>
@endsection
