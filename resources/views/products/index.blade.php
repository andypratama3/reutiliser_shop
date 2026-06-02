@extends('layouts.app')
@section('title', 'Semua Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex gap-8">
        <aside class="w-64 flex-shrink-0 hidden lg:block">
            <h3 class="font-label-caps text-label-caps text-on-surface mb-4 tracking-wider">KATEGORI</h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('products.index') }}"
                       class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors {{ !request('category') ? 'font-semibold text-primary' : '' }}">
                        Semua
                    </a>
                </li>
                @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                           class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors {{ request('category') === $cat->slug ? 'font-semibold text-primary' : '' }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="flex-1">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <p class="font-body-md text-body-md text-on-surface-variant">{{ $products->total() }} produk</p>
                <select onchange="window.location=this.value"
                        class="border border-outline-variant px-4 py-2 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary">
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'newest'])) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                        Terbaru
                    </option>
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                        Harga Terendah
                    </option>
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                        Harga Tertinggi
                    </option>
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'best_selling'])) }}" {{ request('sort') === 'best_selling' ? 'selected' : '' }}>
                        Terlaris
                    </option>
                </select>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}"
                       class="bg-surface border border-outline-variant hover:shadow-lg transition-shadow group">
                        <div class="relative aspect-square overflow-hidden bg-surface-container">
                            @if($product->primaryImage)
                                <img src="{{ Storage::url($product->primaryImage->path) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant font-headline text-4xl">
                                    &nbsp;
                                </div>
                            @endif
                            @if($product->is_limited_edition)
                                <span class="absolute top-2 left-2 bg-primary text-on-primary font-label-caps text-label-caps px-2 py-0.5 tracking-wider">LIMITED</span>
                            @endif
                            @if($product->discount_percent > 0)
                                <span class="absolute top-2 right-2 bg-error text-on-primary font-label-caps text-label-caps px-2 py-0.5">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="font-body-md text-body-md text-on-surface line-clamp-2">{{ $product->name }}</p>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="font-semibold text-on-surface">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @if($product->compare_price)
                                    <span class="font-body-md text-body-md text-on-surface-variant line-through">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            @if($product->isOutOfStock())
                                <span class="mt-1 font-label-caps text-label-caps text-error">Habis</span>
                            @elseif($product->isLowStock())
                                <span class="mt-1 font-label-caps text-label-caps text-tertiary">Stok terbatas</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-16">
                        <p class="font-body-md text-body-md text-on-surface-variant">Tidak ada produk ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
