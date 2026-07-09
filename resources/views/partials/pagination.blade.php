@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col items-center justify-center gap-2 group">
        
        {{-- Progress Info (Sangat Kecil & Elegan) --}}
        <p class="text-[8px] font-black text-gray-300 uppercase tracking-[0.2em] mb-1 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </p>

        <div class="relative inline-flex items-center bg-white/80 backdrop-blur-md px-10 py-4 rounded-[2.5rem] shadow-2xl shadow-black/[0.04] border border-gray-100 gap-12 transition-all duration-500 hover:shadow-blue-500/[0.05] hover:border-blue-200">
            
            {{-- First Page --}}
            @if (!$paginator->onFirstPage())
                <a href="{{ $paginator->url(1) }}" class="text-[10px] font-black text-gray-300 hover:text-black transition-colors duration-300">1</a>
                
                {{-- Previous Arrow --}}
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="text-blue-500 hover:text-blue-600 transition-all duration-300 hover:scale-125 active:scale-90">
                    <i class="fas fa-chevron-left text-[11px]"></i>
                </a>
            @endif

            {{-- Current Page --}}
            <div class="relative flex flex-col items-center">
                <span class="text-base font-black text-gray-900 tracking-tighter transform transition-transform group-hover:scale-110">
                    {{ $paginator->currentPage() }}
                </span>
                <div class="absolute -bottom-1 w-1 h-1 bg-blue-600 rounded-full"></div>
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                {{-- Next Arrow --}}
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="text-blue-500 hover:text-blue-600 transition-all duration-300 hover:scale-125 active:scale-90">
                    <i class="fas fa-chevron-right text-[11px]"></i>
                </a>

                {{-- Last Page --}}
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="text-[10px] font-black text-gray-300 hover:text-black transition-colors duration-300">{{ $paginator->lastPage() }}</a>
            @endif

            {{-- Bottom Progress Line --}}
            @php
                $progress = ($paginator->currentPage() / $paginator->lastPage()) * 100;
            @endphp
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-16 h-[2px] bg-gray-50 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600/30 transition-all duration-1000" style="width: {{ $progress }}%"></div>
            </div>

        </div>
    </nav>
@endif
