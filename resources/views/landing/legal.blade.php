@extends('layouts.landing')

@section('title', $title . ' | RÉUTILISER')

@section('content')
<main class="max-w-3xl mx-auto px-margin-mobile py-24">
    <div class="reveal-item">
        <h1 class="font-headline-lg text-headline-lg text-primary mb-16">{{ $title }}</h1>
        <div class="prose prose-lg prose-primary font-body-md text-secondary space-y-8">
            <p>Last Updated: June 06, 2026</p>
            <p>
                Welcome to RÉUTILISER. Your privacy and trust are paramount to our circular mission. This policy outlines how we handle your data with transparency and care.
            </p>
            <h2 class="font-headline-md text-primary pt-8">1. Data Collection</h2>
            <p>
                We collect only the necessary information to facilitate your conscious purchase and provide size guidance.
            </p>
            <h2 class="font-headline-md text-primary pt-8">2. Radical Transparency</h2>
            <p>
                Just as we are transparent about our textile sources, we are transparent about our data sources. We do not sell your information to third parties.
            </p>
            <h2 class="font-headline-md text-primary pt-8">3. Ethical Communication</h2>
            <p>
                Our newsletter is designed to provide value, not noise. You can opt-out at any time.
            </p>
        </div>
    </div>
</main>
@endsection
