@extends('layouts.landing')

@section('title', 'Shop All | RÉUTILISER Conscious Luxury')

@section('content')
<main class="max-w-[1440px] mx-auto px-8 md:px-16 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-3 space-y-16 reveal-item lg:sticky lg:top-40 h-fit">
            <!-- Shop Internal Search -->
            <form action="{{ url('/shop') }}" method="GET" class="bg-primary p-8 rounded-[2rem] shadow-xl">
                <h3 class="font-label-caps text-white mb-6 text-[11px] tracking-[0.3em] uppercase opacity-60">Quick Find</h3>
                <div class="relative group">
                    <input type="text" name="q" value="{{ request('q') }}" class="w-full bg-white/10 border border-white/20 py-4 pl-12 pr-6 rounded-full text-sm font-body-md focus:bg-white focus:text-primary placeholder:text-white/30 text-white transition-all shadow-inner" placeholder="Search archives..."/>
                    <button type="submit" class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-white opacity-60 cursor-pointer">search</button>
                </div>
                @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
            </form>

            <!-- Categories -->
            <div class="px-4">
                <h3 class="font-label-caps text-primary mb-10 text-[11px] tracking-[0.3em] uppercase opacity-40 border-b border-primary/5 pb-4">Categories</h3>
                <ul class="space-y-6">
                    <li>
                        <a href="{{ url('/shop') }}" class="block w-full text-left font-headline-md text-2xl md:text-3xl {{ !request('category') ? 'text-primary italic' : 'text-secondary opacity-40 hover:opacity-100 hover:translate-x-2' }} transition-all duration-500">
                            Shop All
                        </a>
                    </li>
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ url('/shop?category=' . $cat->slug . (request('sort') ? '&sort=' . request('sort') : '')) }}" class="block w-full text-left font-headline-md text-2xl md:text-3xl {{ request('category') === $cat->slug ? 'text-primary italic' : 'text-secondary opacity-40 hover:opacity-100 hover:translate-x-2' }} transition-all duration-500">
                            {{ $cat->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Price Range -->
            <div class="px-4">
                <h3 class="font-label-caps text-primary mb-10 text-[11px] tracking-[0.3em] uppercase opacity-40 border-b border-primary/5 pb-4">Price Range</h3>
                <form action="{{ url('/shop') }}" method="GET" class="space-y-4">
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @php
                        $ranges = [
                            '' => 'All Prices',
                            '0-200000' => 'Under Rp 200K',
                            '200000-500000' => 'Rp 200K — Rp 500K',
                            '500001-999999999' => 'Above Rp 500K',
                        ];
                        $selectedRange = request('price_min') !== null ? request('price_min') . '-' . request('price_max') : '';
                    @endphp
                    @foreach($ranges as $val => $label)
                    <label class="flex items-center gap-4 cursor-pointer group">
                        <div class="w-6 h-6 rounded-full bg-surface-container-low flex items-center justify-center group-hover:bg-primary/5 transition-all">
                            <input type="radio" name="price_range" value="{{ $val }}" {{ $selectedRange === $val ? 'checked' : '' }}
                                   onchange="this.form.submit()"
                                   class="w-3 h-3 text-primary border-0 focus:ring-0 cursor-pointer"/>
                        </div>
                        <span class="font-body-md text-lg text-secondary opacity-60 group-hover:opacity-100 transition-all">{{ $label }}</span>
                    </label>
                    @endforeach
                </form>
            </div>

            <!-- Sustainability Note -->
            <div class="bg-primary p-8 rounded-[2.5rem] shadow-xl reveal-item">
                <span class="material-symbols-outlined mb-6 text-3xl text-white opacity-60">verified</span>
                <p class="font-body-md italic text-lg leading-relaxed text-white opacity-90">
                    "Every piece is a singular artifact of our reconstruction process. Archive your selection before it returns to the void."
                </p>
            </div>
        </aside>

        <!-- Product Listing -->
        <div class="lg:col-span-9">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8 reveal-item">
                <div>
                    <p class="font-label-caps text-secondary text-[11px] tracking-[0.4em] uppercase opacity-40 mb-2">Curated Drop</p>
                    <h2 class="font-display-lg text-5xl text-primary">All Artifacts</h2>
                    <p class="font-label-caps text-[10px] text-secondary tracking-widest mt-2 opacity-40">{{ $paginator->total() }} piece(s)</p>
                </div>
                
                <!-- Advanced Sort -->
                <form action="{{ url('/shop') }}" method="GET" class="flex items-center gap-6 bg-surface-container-low p-3 rounded-full border border-primary/5">
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    @if(request('price_min'))
                    <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                    @endif
                    @if(request('price_max'))
                    <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                    @endif
                    <span class="font-label-caps text-[10px] text-secondary tracking-widest pl-4">SORT BY:</span>
                    <select name="sort" onchange="this.form.submit()" class="bg-white border-0 rounded-full px-8 py-3 font-label-caps text-sm text-primary focus:ring-2 focus:ring-primary/5 cursor-pointer tracking-widest shadow-sm">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Latest Discovery</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-16 md:gap-x-12 md:gap-y-20">
                @forelse($products as $product)
                <div class="group reveal-item">
                    <div class="aspect-[3/4] rounded-[2.5rem] bg-surface-container-low mb-8 relative overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-700">
                        <img src="{{ $product['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2000ms]" alt="{{ $product['name'] }}">
                        
                        <!-- Hover Quick Action -->
                        <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-4 p-6">
                            <a href="{{ $product['slug'] ? url('/products/' . $product['slug']) : url('/product/' . $product['id']) }}" class="w-full bg-white text-primary py-5 rounded-full font-label-caps text-center text-xs tracking-[0.2em] shadow-2xl hover:bg-primary hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-500">VIEW ARTIFACT</a>
                            @if(!$product['is_out_of_stock'])
                            <form method="POST" action="{{ route('cart.add') }}" class="w-full">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-white/90 text-primary py-4 rounded-full font-label-caps text-center text-[10px] tracking-[0.15em] shadow-lg hover:bg-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-500 delay-75">ADD TO ARCHIVE</button>
                            </form>
                            @endif
                        </div>

                        @if($product['tag'])
                        <span class="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-5 py-2 rounded-full font-label-caps text-[9px] text-primary tracking-widest shadow-sm">{{ $product['tag'] }}</span>
                        @endif
                        @if($product['is_out_of_stock'])
                        <span class="absolute top-6 right-6 bg-red-900/80 backdrop-blur-md px-4 py-2 rounded-full font-label-caps text-[9px] text-white tracking-widest shadow-sm">SOLD</span>
                        @endif
                    </div>
                    <div class="px-2 space-y-3">
                        <div class="flex justify-between items-start">
                            <h3 class="font-headline-md text-2xl text-primary leading-tight group-hover:italic transition-all">{{ $product['name'] }}</h3>
                            <div class="text-right">
                                <span class="font-body-md text-xl text-primary font-bold">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                                @if($product['compare_price'])
                                <br><span class="font-label-caps text-[10px] text-secondary line-through">Rp {{ number_format($product['compare_price'], 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                        <p class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-60">{{ $product['material'] }}</p>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-32">
                    <span class="material-symbols-outlined text-6xl text-secondary opacity-20 mb-6">search_off</span>
                    <p class="font-headline-md text-2xl text-secondary opacity-60">No artifacts found.</p>
                    <a href="{{ url('/shop') }}" class="inline-block mt-8 border border-primary px-12 py-4 rounded-full font-label-caps text-[11px] text-primary hover:bg-primary hover:text-white transition-all">CLEAR FILTERS</a>
                </div>
                @endforelse
            </section>

            <!-- Pagination -->
            @if($paginator->hasPages())
            <div class="mt-32 flex justify-center gap-4 reveal-item">
                @if($paginator->onFirstPage())
                    <span class="px-6 py-4 border border-primary/20 rounded-full font-label-caps text-[10px] text-secondary opacity-30 tracking-widest cursor-not-allowed">PREVIOUS</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-6 py-4 border border-primary/20 hover:border-primary rounded-full font-label-caps text-[10px] text-primary tracking-widest transition-all">PREVIOUS</a>
                @endif
                
                <div class="flex gap-2">
                    @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}" class="w-12 h-12 flex items-center justify-center border rounded-full font-label-caps text-[11px] {{ $page === $paginator->currentPage() ? 'bg-primary text-white border-primary' : 'border-primary/20 text-primary hover:border-primary' }} transition-all">
                            {{ $page }}
                        </a>
                    @endforeach
                </div>

                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-6 py-4 border border-primary/20 hover:border-primary rounded-full font-label-caps text-[10px] text-primary tracking-widest transition-all">NEXT</a>
                @else
                    <span class="px-6 py-4 border border-primary/20 rounded-full font-label-caps text-[10px] text-secondary opacity-30 tracking-widest cursor-not-allowed">NEXT</span>
                @endif
            </div>
            @endif
        </div>
    </div>
</main>
@endsection
