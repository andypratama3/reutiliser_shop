@extends('layouts.app')
@section('title', config('app.name') . ' | Conscious Luxury for a Circular Future')

@section('content')
<section class="relative min-h-[70vh] flex items-center bg-primary">
    <div class="max-w-7xl mx-auto px-4 py-20 w-full">
        <div class="max-w-2xl">
            <p class="font-label-caps text-label-caps text-primary-fixed-dim tracking-widest mb-4">SUSTAINABLE FASHION</p>
            <h1 class="font-headline text-5xl md:text-6xl text-on-primary leading-tight mb-6">
                Wear More,<br>
                <span class="italic">Waste Less</span>
            </h1>
            <p class="font-body-lg text-body-lg text-primary-fixed-dim mb-10 max-w-lg">
                Kurated fashion dari limbah industri, dikolaborasikan dengan designer lokal.
            </p>
            <a href="{{ route('products.index') }}" class="inline-block bg-on-primary text-primary px-10 py-4 font-label-caps text-label-caps hover:opacity-90 transition-opacity">
                Belanja Sekarang
            </a>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-20">
    <div class="flex justify-between items-end mb-12 border-b border-outline-variant pb-6">
        <div>
            <h2 class="font-headline text-3xl text-primary">Featured Products</h2>
            <p class="font-label-caps text-label-caps text-on-surface-variant tracking-wider mt-2">PILIHAN TERBAIK KAMI</p>
        </div>
        <a href="{{ route('products.index') }}" class="font-label-caps text-label-caps text-primary border-b-2 border-primary pb-1 hover:opacity-70 transition-opacity">
            Lihat Semua
        </a>
    </div>

    @php
        $featured = \App\Models\Product::active()->featured()->with('primaryImage')->latest()->take(4)->get();
    @endphp

    @if($featured->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featured as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="group">
                    <div class="bg-surface-container aspect-[3/4] overflow-hidden mb-4">
                        @if($product->primaryImage)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($product->primaryImage->path) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-on-surface-variant font-headline text-4xl">
                                &nbsp;
                            </div>
                        @endif
                    </div>
                    <h3 class="font-headline text-body-lg text-on-surface">{{ $product->name }}</h3>
                    <p class="font-label-caps text-label-caps text-on-surface-variant mt-1">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16">
            <p class="font-body-md text-body-md text-on-surface-variant">Belum ada produk featured.</p>
        </div>
    @endif
</section>

<section class="bg-surface-container-low py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="font-headline text-3xl text-primary mb-4">About RÉUTILISER</h2>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
            Réutiliser merupakan hasil kolaborasi antara sebuah brand fashion dengan designer lokal
            yang kembali mengolah limbah industri fashion menjadi fashion item limited yang bernilai jual tinggi.
        </p>
    </div>
</section>
@endsection
