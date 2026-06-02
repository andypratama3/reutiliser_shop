@extends('layouts.landing')

@section('title', $product['name'] . ' | RÉUTILISER')

@section('content')
<main class="max-w-container-max mx-auto px-4 md:px-12 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        <!-- Product Images -->
        <div class="lg:col-span-7 space-y-8 reveal-item">
            <div class="aspect-[3/4] bg-secondary-container rounded-[2rem] overflow-hidden border border-primary p-2">
                <img src="{{ $product['image'] }}" class="w-full h-full object-cover rounded-[1.5rem]" alt="{{ $product['name'] }}">
            </div>
            <div class="grid grid-cols-2 gap-8">
                <div class="aspect-square bg-surface-container-low rounded-[1.5rem] overflow-hidden border border-primary/20 p-2">
                    <img src="{{ $product['image'] }}" class="w-full h-full object-cover rounded-[1rem] grayscale opacity-60" alt="Detail 1">
                </div>
                <div class="aspect-square bg-surface-container-low rounded-[1.5rem] overflow-hidden border border-primary/20 p-2">
                    <img src="{{ $product['image'] }}" class="w-full h-full object-cover rounded-[1rem] brightness-50" alt="Detail 2">
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <aside class="lg:col-span-5 space-y-12 reveal-item border border-primary p-12 rounded-[2.5rem] bg-surface h-fit">
            <div>
                <p class="font-label-caps text-secondary mb-4 tracking-widest text-[12px]">{{ $product['category'] }} / {{ $product['material'] }}</p>
                <h1 class="font-headline-lg text-primary leading-tight mb-4">{{ $product['name'] }}</h1>
                <p class="font-headline-md text-primary text-3xl font-bold">${{ number_format($product['price'], 0) }}</p>
            </div>

            <p class="font-body-lg text-secondary leading-relaxed italic border-l-2 border-primary pl-6">
                "{{ $product['description'] }}"
            </p>

            <div class="space-y-8">
                <div>
                    <h4 class="font-label-caps text-[10px] text-primary mb-6 tracking-widest">SIZE GUIDE</h4>
                    <div class="flex gap-4">
                        @foreach(['S', 'M', 'L', 'XL'] as $size)
                        <button class="w-12 h-12 border border-primary rounded-full flex items-center justify-center font-label-caps text-[12px] hover:bg-primary hover:text-white transition-all {{ $size == 'M' ? 'bg-primary text-white' : '' }}">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <button class="w-full bg-primary text-on-primary py-6 rounded-full font-label-caps tracking-widest hover:bg-primary-container transition-all border border-primary">ADD TO ARCHIVE</button>
                    <button class="w-full border border-primary py-4 rounded-full flex items-center justify-center gap-4 hover:bg-primary/5 transition-colors">
                        <span class="material-symbols-outlined scale-90">favorite</span>
                        <span class="font-label-caps text-[10px] tracking-widest">SAVE TO WISHLIST</span>
                    </button>
                </div>
            </div>

            <div class="pt-12 border-t border-primary/20 space-y-6">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 flex items-center justify-center border border-primary rounded-full">
                        <span class="material-symbols-outlined text-primary scale-90">eco</span>
                    </div>
                    <p class="font-body-md text-secondary text-sm italic">Carbon-Neutral Shipping Included</p>
                </div>
            </div>
        </aside>
    </div>

    <!-- Related Products -->
    <section class="mt-32 border-t border-primary/10 pt-24 reveal-item">
        <h2 class="font-headline-md text-3xl text-primary mb-16">You may also appreciate</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @foreach($relatedProducts as $rel)
            <a href="{{ url('/product/' . $rel['id']) }}" class="group block">
                <div class="border border-primary p-3 rounded-[2rem] bg-surface-container-low mb-6 overflow-hidden relative">
                    <div class="aspect-[3/4] rounded-[1.5rem] overflow-hidden">
                        <img src="{{ $rel['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="{{ $rel['name'] }}">
                    </div>
                </div>
                <h3 class="font-body-md text-primary font-bold">{{ $rel['name'] }}</h3>
                <p class="font-label-caps text-[10px] text-secondary mt-1 uppercase">${{ number_format($rel['price'], 0) }}</p>
            </a>
            @endforeach
        </div>
    </section>
</main>
@endsection
