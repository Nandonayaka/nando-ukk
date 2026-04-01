@extends('books.layout')

@section('content')
<!-- Recommendation Banner -->
@if($featuredBooks->count() > 0)
<div class="mb-10 relative rounded-2xl overflow-hidden bg-gray-900 shadow-lg h-[200px] md:h-[350px]">
    <!-- Background Layer -->
    <div id="banner-bg" class="absolute inset-0 flex transition-transform duration-700 h-full">
        @foreach($featuredBooks as $item)
        <div class="min-w-full h-full relative">
            <img src="{{ Str::startsWith($item->gambar, 'http') ? $item->gambar : asset('img/' . $item->gambar) }}" class="w-full h-full object-cover blur-lg opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent lg:bg-gradient-to-r"></div>
        </div>
        @endforeach
    </div>

    <!-- Content Layer -->
    <div id="banner-content" class="relative z-10 flex transition-transform duration-700 h-full">
        @foreach($featuredBooks as $item)
        <div class="min-w-full h-full flex items-center px-6 md:px-16 gap-6 md:gap-12">
            <div class="flex-shrink-0">
                <img src="{{ Str::startsWith($item->gambar, 'http') ? $item->gambar : asset('img/' . $item->gambar) }}" class="h-32 w-24 md:h-64 md:w-44 object-cover rounded-lg shadow-2xl border border-white/20">
            </div>
            <div class="flex-1 min-w-0">
                <span class="inline-block bg-white/10 backdrop-blur-md text-white/80 px-2 py-0.5 rounded text-[8px] md:text-[10px] font-bold uppercase tracking-widest border border-white/10 mb-2">Pilihan Utama</span>
                <h2 class="text-xl md:text-5xl font-bold text-white leading-tight truncate mb-1 md:mb-3">{{ $item->judul }}</h2>
                <p class="text-[10px] md:text-sm text-gray-300 line-clamp-2 md:line-clamp-3 max-w-md">{{ $item->deskripsi }}</p>
                <div class="mt-3 md:mt-6">
                    <a href="{{ route('books.show', $item->id) }}" class="inline-flex items-center gap-2 bg-white text-black px-4 py-2 md:px-8 md:py-3 rounded-lg font-bold text-[10px] md:text-[12px] hover:bg-gray-100 transition shadow-lg">
                        Lihat Buku
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Controls -->
    <div class="absolute bottom-4 left-6 md:left-16 flex gap-1.5 md:gap-2">
        @foreach($featuredBooks as $k => $it)
        <div class="dot-btn h-1 md:h-1.5 w-4 md:w-8 bg-white/20 rounded-full transition-all duration-300" data-index="{{ $k }}"></div>
        @endforeach
    </div>
</div>

<script>
    let currentSlide = 0;
    const bgSlider = document.getElementById('banner-bg');
    const contentSlider = document.getElementById('banner-content');
    const dots = document.querySelectorAll('.dot-btn');
    const slideCount = {{ $featuredBooks->count() }};

    function updateSlider() {
        const offset = `-${currentSlide * 100}%`;
        bgSlider.style.transform = `translateX(${offset})`;
        contentSlider.style.transform = `translateX(${offset})`;
        dots.forEach((dot, idx) => {
            dot.classList.toggle('bg-white', idx === currentSlide);
            dot.classList.toggle('w-8', idx === currentSlide && window.innerWidth < 768);
            dot.classList.toggle('w-12', idx === currentSlide && window.innerWidth >= 768);
            dot.classList.toggle('bg-white/20', idx !== currentSlide);
            dot.classList.toggle('w-4', idx !== currentSlide && window.innerWidth < 768);
            dot.classList.toggle('w-8', idx !== currentSlide && window.innerWidth >= 768);
        });
    }

    setInterval(() => {
        currentSlide = (currentSlide + 1) % slideCount;
        updateSlider();
    }, 5000);
    updateSlider();
</script>
@endif

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 px-1">
    <div class="flex items-center gap-3">
        <h1 class="text-2xl font-bold text-black tracking-tight">Semua Buku</h1>
        <span class="bg-gray-100 text-[10px] font-black text-gray-400 px-3 py-1 rounded-full uppercase tracking-tighter">
            {{ $books->count() }} Koleksi
        </span>
    </div>
    
    <div class="w-full md:w-96 relative">
        <form action="{{ route('katalog.index') }}" method="GET">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Koleksi Pustaka..." 
                class="w-full bg-white border border-gray-100 rounded-2xl px-12 py-3.5 text-[12px] font-semibold text-black placeholder-gray-300 focus:border-black focus:ring-4 focus:ring-black/5 transition-all shadow-sm">
        </form>
    </div>
</div>

<div class="grid grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-8">
    @forelse($books as $book)
        <a href="{{ route('books.show', $book->id) }}" class="group flex flex-col bg-white transition-all duration-300">
            <div class="aspect-[2/3] rounded-lg overflow-hidden mb-2 bg-gray-50 relative shadow-sm transition-shadow">
                @if($book->gambar)
                    <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105 duration-700">
                @else
                    <div class="w-full h-full flex items-center justify-center p-4 bg-gray-100">
                        <i class="fas fa-book-open text-gray-200 text-2xl"></i>
                    </div>
                @endif

                @if($book->stok <= 0)
                <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center">
                    <span class="bg-black/80 text-white text-[7px] font-bold px-2 py-0.5 rounded-full uppercase tracking-tighter">Habis</span>
                </div>
                @endif
            </div>

            <div class="space-y-0.5">
                <h3 class="font-bold text-gray-900 group-hover:text-black text-[10px] md:text-sm line-clamp-1 leading-tight">{{ $book->judul }}</h3>
                <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-tight truncate">{{ $book->penulis }}</p>
                <div class="pt-0.5">
                    <span class="text-[9px] md:text-[11px] font-black text-black">{{ $book->harga ? 'Rp' . number_format($book->harga, 0, ',', '.') : 'GRATIS' }}</span>
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full py-20 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
            <p class="text-gray-400 font-bold text-xs tracking-widest uppercase">Belum ada koleksi.</p>
        </div>
    @endforelse
</div>
@endsection
