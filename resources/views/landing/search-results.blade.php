@extends('layouts.landing')

@section('title', 'Search Results | RÉUTILISER')

@section('content')
<main class="max-w-[1600px] mx-auto px-8 md:px-16 py-24">
    <div class="mb-32 reveal-item">
        <p class="font-label-caps text-secondary mb-6 tracking-[0.4em] text-sm opacity-60">EXPLORING THE ARCHIVES</p>
        <h1 class="font-display-lg text-primary leading-tight">Results for: <span class="italic font-light">"{{ $query }}"</span></h1>
        <p class="font-body-md text-secondary mt-8 text-xl">{{ $results->count() }} unique artifacts found</p>
    </div>

    @if($results->isEmpty())
    <section class="py-32 text-center reveal-item bg-surface-container-low rounded-[3rem]">
        <h2 class="font-headline-md text-4xl text-primary mb-12">No exact matches in our current archive.</h2>
        <a href="{{ url('/shop') }}" class="bg-primary text-on-primary px-12 py-6 rounded-full font-label-caps tracking-widest hover:bg-primary-container transition-all shadow-xl">EXPLORE ALL ARCHIVES</a>
    </section>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 md:gap-24">
        @foreach($results as $product)
        <div class="group reveal-item">
            <div class="aspect-[3/4] rounded-[2.5rem] overflow-hidden mb-10 shadow-2xl relative">
                <img src="{{ $product['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="{{ $product['name'] }}">
                <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3 p-6">
                    <a href="{{ $product['slug'] ? url('/products/' . $product['slug']) : url('/product/' . $product['id']) }}" class="bg-white text-primary px-10 py-4 rounded-full font-label-caps text-[10px] tracking-widest shadow-2xl hover:bg-primary hover:text-white transition-all border border-primary">VIEW PIECE</a>
                    @if(!$product['is_out_of_stock'])
                    <form method="POST" action="{{ route('cart.add') }}" class="w-full max-w-[200px]">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-white/90 text-primary py-3 rounded-full font-label-caps text-[9px] tracking-widest shadow-lg hover:bg-white transition-all border border-primary">ADD TO ARCHIVE</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <h3 class="font-body-lg text-2xl text-primary font-bold">{{ $product['name'] }}</h3>
                    <span class="font-headline-md text-2xl text-primary">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                </div>
                <p class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-60">{{ $product['material'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</main>
@endsection
