@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-4 reveal-item">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-12 h-12 flex items-center justify-center rounded-full border border-primary/5 text-primary/20 cursor-not-allowed">
                <span class="material-symbols-outlined text-sm">west</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-12 h-12 flex items-center justify-center rounded-full border border-primary/20 text-primary hover:bg-primary hover:text-white transition-all shadow-sm">
                <span class="material-symbols-outlined text-sm">west</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center gap-2">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="font-label-caps text-[10px] text-secondary opacity-40 mx-2">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-12 h-12 flex items-center justify-center rounded-full bg-primary text-white font-label-caps text-[10px] shadow-xl">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-12 h-12 flex items-center justify-center rounded-full border border-primary/5 text-secondary hover:border-primary/20 hover:text-primary transition-all font-label-caps text-[10px]">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-12 h-12 flex items-center justify-center rounded-full border border-primary/20 text-primary hover:bg-primary hover:text-white transition-all shadow-sm">
                <span class="material-symbols-outlined text-sm">east</span>
            </a>
        @else
            <span class="w-12 h-12 flex items-center justify-center rounded-full border border-primary/5 text-primary/20 cursor-not-allowed">
                <span class="material-symbols-outlined text-sm">east</span>
            </span>
        @endif
    </nav>
@endif
