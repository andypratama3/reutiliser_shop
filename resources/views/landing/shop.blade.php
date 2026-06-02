@extends('layouts.landing')

@section('title', 'Shop All | RÉUTILISER Conscious Luxury')

@section('content')
<main class="max-w-[1440px] mx-auto px-8 md:px-16 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-3 space-y-16 reveal-item lg:sticky lg:top-40 h-fit">
            <!-- Shop Internal Search -->
            <div class="bg-primary p-8 rounded-[2rem] shadow-xl">
                <h3 class="font-label-caps text-white mb-6 text-[11px] tracking-[0.3em] uppercase opacity-60">Quick Find</h3>
                <div class="relative group">
                    <input type="text" class="w-full bg-white/10 border border-white/20 py-4 pl-12 pr-6 rounded-full text-sm font-body-md focus:bg-white focus:text-primary placeholder:text-white/30 text-white transition-all shadow-inner" placeholder="Search archives..."/>
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-white opacity-60">search</span>
                </div>
            </div>

            <!-- Categories -->
            <div class="px-4">
                <h3 class="font-label-caps text-primary mb-10 text-[11px] tracking-[0.3em] uppercase opacity-40 border-b border-primary/5 pb-4">Categories</h3>
                <ul class="space-y-6">
                    @foreach(['Shop All', 'Jackets', 'Trousers', 'Shirts', 'Accessories'] as $cat)
                    <li>
                        <button class="w-full text-left font-headline-md text-2xl md:text-3xl {{ $cat == 'Shop All' ? 'text-primary italic' : 'text-secondary opacity-40 hover:opacity-100 hover:translate-x-2' }} transition-all duration-500">
                            {{ $cat }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Price Range -->
            <div class="px-4">
                <h3 class="font-label-caps text-primary mb-10 text-[11px] tracking-[0.3em] uppercase opacity-40 border-b border-primary/5 pb-4">Price Range</h3>
                <div class="space-y-4">
                    @foreach(['Under $200', '$200 — $400', 'Above $400'] as $range)
                    <label class="flex items-center gap-4 cursor-pointer group">
                        <div class="w-6 h-6 rounded-full bg-surface-container-low flex items-center justify-center group-hover:bg-primary/5 transition-all">
                            <input type="radio" name="price" class="w-3 h-3 text-primary border-0 focus:ring-0 cursor-pointer"/>
                        </div>
                        <span class="font-body-md text-lg text-secondary opacity-60 group-hover:opacity-100 transition-all">{{ $range }}</span>
                    </label>
                    @endforeach
                </div>
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
                </div>
                
                <!-- Advanced Sort -->
                <div class="flex items-center gap-6 bg-surface-container-low p-3 rounded-full border border-primary/5">
                    <span class="font-label-caps text-[10px] text-secondary tracking-widest pl-4">SORT BY:</span>
                    <select class="bg-white border-0 rounded-full px-8 py-3 font-label-caps text-sm text-primary focus:ring-2 focus:ring-primary/5 cursor-pointer tracking-widest shadow-sm">
                        <option>Latest Discovery</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Material Rarity</option>
                    </select>
                </div>
            </div>

            <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-16 md:gap-x-12 md:gap-y-20">
                @foreach($products as $product)
                <div class="group reveal-item">
                    <div class="aspect-[3/4] rounded-[2.5rem] bg-surface-container-low mb-8 relative overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-700">
                        <img src="{{ $product['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2000ms]" alt="{{ $product['name'] }}">
                        
                        <!-- Hover Quick Action -->
                        <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                            <a href="{{ url('/product/' . $product['id']) }}" class="w-full bg-white text-primary py-5 rounded-full font-label-caps text-center text-xs tracking-[0.2em] shadow-2xl hover:bg-primary hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-500">VIEW ARTIFACT</a>
                        </div>

                        @if($product['tag'])
                        <span class="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-5 py-2 rounded-full font-label-caps text-[9px] text-primary tracking-widest shadow-sm">{{ $product['tag'] }}</span>
                        @endif
                    </div>
                    <div class="px-2 space-y-3">
                        <div class="flex justify-between items-start">
                            <h3 class="font-headline-md text-2xl text-primary leading-tight group-hover:italic transition-all">{{ $product['name'] }}</h3>
                            <span class="font-body-md text-xl text-primary font-bold">${{ number_format($product['price'], 0) }}</span>
                        </div>
                        <p class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-60">{{ $product['material'] }}</p>
                    </div>
                </div>
                @endforeach
            </section>

            <!-- Loading Simulation -->
            <div class="mt-32 text-center reveal-item">
                <button class="group inline-flex items-center gap-6 px-16 py-6 border border-primary/20 hover:border-primary rounded-full transition-all">
                    <span class="font-label-caps text-primary tracking-widest">LOAD MORE ARCHIVES</span>
                    <span class="material-symbols-outlined text-primary group-hover:rotate-180 transition-transform">autorenew</span>
                </button>
            </div>
        </div>
    </div>
</main>
@endsection
