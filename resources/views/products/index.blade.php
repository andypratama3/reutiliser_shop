@extends('layouts.landing')

@section('title', 'All Archives | RÉUTILISER')

@section('content')

<main class="max-w-[1440px] mx-auto px-8 md:px-16 py-12">
    <!-- Page Header -->
    <div class="mb-20 reveal-item text-center md:text-left flex flex-col md:flex-row justify-between items-end gap-8">
        <div>
            <h1 class="font-display-lg text-primary mb-4 uppercase tracking-tighter">The Collection</h1>
            <p class="font-body-md text-secondary max-w-lg text-lg opacity-60 italic">Exploring the intersection of archival discovery and circular reconstruction.</p>
        </div>
        <div class="flex items-center gap-6">
            <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40">{{ $products->total() }} Artifacts found</p>
            <select onchange="window.location=this.value"
                    class="bg-surface-container-low border-none rounded-2xl px-6 py-3 font-label-caps text-[10px] tracking-widest text-primary focus:ring-2 focus:ring-primary/5 cursor-pointer uppercase outline-none">
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'newest'])) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Discovery</option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Ascending Price</option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Descending Price</option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'best_selling'])) }}" {{ request('sort') === 'best_selling' ? 'selected' : '' }}>Highly Appreciated</option>
            </select>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-16">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 flex-shrink-0 reveal-item">
            <div class="sticky top-32 space-y-12">
                <div>
                    <h3 class="font-label-caps text-[11px] text-primary mb-8 tracking-[0.3em] uppercase opacity-40">Categories</h3>
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('products.index') }}"
                               class="group flex items-center justify-between font-body-md text-sm transition-all {{ !request('category') ? 'text-primary font-bold' : 'text-secondary opacity-60 hover:opacity-100' }}">
                                <span>All Fragments</span>
                                <span class="material-symbols-outlined text-sm opacity-0 group-hover:opacity-100 transition-all">east</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                                   class="group flex items-center justify-between font-body-md text-sm transition-all {{ request('category') === $cat->slug ? 'text-primary font-bold' : 'text-secondary opacity-60 hover:opacity-100' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="material-symbols-outlined text-sm opacity-0 group-hover:opacity-100 transition-all">east</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="p-8 bg-primary/5 rounded-[2rem] border border-primary/5">
                    <h4 class="font-headline-md text-xl text-primary mb-4">Radical Transparency</h4>
                    <p class="font-body-md text-xs text-secondary leading-relaxed opacity-60 italic">Every piece is reconstructed from archival luxury remnants, ensuring a zero-waste circular future.</p>
                </div>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 reveal-item">
                @forelse($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="group block">
                        <div class="bg-white p-4 rounded-[2.5rem] border border-primary/5 mb-8 overflow-hidden relative shadow-sm hover:shadow-2xl transition-all">
                            <div class="aspect-[3/4] rounded-[2rem] overflow-hidden">
                                <img src="{{ $product->primary_image_url }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2000ms]">
                            </div>
                            
                            @if($product->is_limited_edition)
                                <span class="absolute top-8 left-8 bg-primary text-white font-label-caps text-[9px] px-4 py-1.5 rounded-full tracking-widest shadow-xl">LIMITED</span>
                            @endif
                            
                            @if($product->discount_percent > 0)
                                <span class="absolute top-8 right-8 bg-red-500 text-white font-label-caps text-[9px] px-4 py-1.5 rounded-full tracking-widest shadow-xl">-{{ $product->discount_percent }}%</span>
                            @endif

                            @if($product->isOutOfStock())
                                <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="font-label-caps text-[11px] tracking-[0.4em] text-primary font-bold">ARCHIVED</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="px-2">
                            <h3 class="font-body-md text-primary font-bold text-xl mb-1 group-hover:italic transition-all">{{ $product->name }}</h3>
                            <div class="flex items-center gap-4">
                                <p class="font-label-caps text-[11px] text-secondary opacity-40 tracking-widest uppercase">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @if($product->compare_price)
                                    <p class="font-label-caps text-[10px] text-secondary/30 line-through tracking-widest">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-32">
                        <span class="material-symbols-outlined text-6xl text-secondary opacity-20 mb-8">inventory_2</span>
                        <p class="font-body-lg text-secondary italic">No archival artifacts found matching your criteria.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-20">
                {{ $products->links('partials.pagination-landing') }}
            </div>
        </div>
    </div>
</main>
@endsection
