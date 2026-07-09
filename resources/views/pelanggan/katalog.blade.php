@extends('books.layout')

@section('content')
@php
    $getBadgeColor = function($id) {
        $colors = [
            'bg-blue-50 text-blue-600 border-blue-100',
            'bg-red-50 text-red-600 border-red-100',
            'bg-emerald-50 text-emerald-600 border-emerald-100',
            'bg-amber-50 text-amber-600 border-amber-100',
            'bg-sky-50 text-sky-600 border-sky-100', // FIXED: Sky blue color replacement for broken line
            'bg-pink-50 text-pink-600 border-pink-100',
            'bg-indigo-50 text-indigo-600 border-indigo-100',
            'bg-teal-50 text-teal-600 border-teal-100',
            'bg-orange-50 text-orange-600 border-orange-100',
            'bg-cyan-50 text-cyan-600 border-cyan-100'
        ];
        return $colors[$id % count($colors)];
    };
@endphp
@if(request('search'))
    <!-- SEARCH RESULTS VIEW -->
    <div class="mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <a href="{{ route('katalog.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-black transition text-xs font-bold uppercase tracking-widest mb-4">
                    <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Jelajah
                </a>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Hasil Pencarian</h1>
                <p class="text-gray-500 text-sm mt-1">Ditemukan {{ $books->total() }} buku untuk kata kunci "<span class="text-black font-bold">{{ request('search') }}</span>"</p>
            </div>

            <div class="w-full md:w-[480px]">
                 <form action="{{ route('katalog.index') }}" method="GET" class="flex items-center gap-3">
                    <div class="relative flex-1 group">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-[10px] group-focus-within:text-black transition"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku lain..." 
                            class="w-full bg-white border border-gray-100 rounded-xl px-10 py-3.5 text-[12px] font-semibold text-black placeholder-gray-300 focus:ring-4 focus:ring-black/5 focus:border-black transition-all shadow-sm">
                    </div>
                    <div class="w-44 shrink-0">
                        @include('partials.filter-dropdown', [
                            'name' => 'category',
                            'options' => $kategoris,
                            'selected' => request('category'),
                            'placeholder' => 'KATEGORI',
                            'align' => 'right-0',
                            'activeBg' => 'bg-[#1d63d6]'
                        ])
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($books as $book)
                <a href="{{ route('books.show', $book->id) }}" class="group bg-white border border-gray-100 p-4 rounded-2xl flex gap-5 hover:shadow-xl hover:shadow-black/5 transition-all duration-300">
                    <div class="w-24 h-36 md:w-32 md:h-44 flex-shrink-0 relative overflow-hidden rounded-xl shadow-md">
                        @if($book->gambar)
                            <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gray-50 flex items-center justify-center">
                                <i class="fas fa-book text-gray-200 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex flex-wrap gap-2 mb-2">
                                @foreach($book->kategoris as $kat)
                                    <span class="{{ $getBadgeColor($kat->id) }} text-[8px] font-bold px-2 py-0.5 rounded uppercase tracking-widest border shadow-sm">{{ $kat->nama_kategori }}</span>
                                @endforeach
                            </div>
                            <h3 class="font-bold text-gray-900 text-base line-clamp-2 leading-tight group-hover:text-black">{{ $book->judul }}</h3>
                            <div class="flex items-center gap-3 mt-1.5">
                                <p class="text-xs font-semibold text-gray-400 italic">oleh {{ $book->penulis }}</p>
                                <div class="flex items-center gap-1 bg-yellow-50 px-2 py-0.5 rounded-lg border border-yellow-100">
                                    <i class="fas fa-star text-yellow-500 text-[10px]"></i>
                                    <span class="text-[10px] font-black text-yellow-700">{{ number_format($book->ulasan_bukus_avg_rating, 1) ?? '0.0' }}</span>
                                    <span class="text-[8px] text-yellow-600/60 font-medium">({{ $book->ulasan_bukus_count ?? 0 }})</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between gap-2 mt-4 text-right">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-black group-hover:text-white transition-colors ml-auto">
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                     <i class="fas fa-search text-gray-200 text-4xl mb-4"></i>
                     <h3 class="text-gray-400 font-bold text-sm tracking-widest uppercase mb-1">Buku Tidak Ditemukan</h3>
                     <p class="text-gray-400 text-xs text-center max-w-xs mx-auto">Coba cari dengan kata kunci lain atau periksa ejaan judul buku.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $books->links() }}
        </div>
    </div>

@else
    <!-- NORMAL JELAJAH VIEW (Banner, Popular, etc.) -->
    @if($featuredBooks->count() > 0)
    <div class="mb-10 relative rounded-3xl overflow-hidden bg-gray-950 shadow-2xl h-[220px] md:h-[400px] group">
        <!-- Slides Container (Fade Effect) -->
        <div id="banner-slides-container" class="absolute inset-0 h-full">
            @foreach($featuredBooks as $k => $item)
            <div class="banner-slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0" data-index="{{ $k }}">
                <!-- Background Layer (Blurred Image Style) -->
                <div class="absolute inset-0 h-full w-full bg-gray-900">
                    <img src="{{ Str::startsWith($item->gambar, 'http') ? $item->gambar : asset('img/' . $item->gambar) }}" class="w-full h-full object-cover scale-110 blur-xl opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/40 to-transparent lg:bg-gradient-to-r"></div>
                </div>

                <!-- Content Layer -->
                <div class="relative z-10 flex items-center h-full px-8 md:px-16 gap-8 md:gap-12 text-white">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <img src="{{ Str::startsWith($item->gambar, 'http') ? $item->gambar : asset('img/' . $item->gambar) }}" class="h-36 w-24 md:h-64 md:w-44 object-cover rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 group-hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="bg-white/10 backdrop-blur-md text-white px-2.5 py-1 rounded text-[8px] md:text-[10px] font-bold uppercase tracking-widest border border-white/10">Terbaru</span>
                        </div>
                        <h2 class="text-2xl md:text-6xl font-bold text-white leading-tight truncate mb-2 md:mb-4 drop-shadow-sm">{{ $item->judul }}</h2>
                        <p class="text-[10px] md:text-sm text-gray-300 line-clamp-2 md:line-clamp-3 max-w-lg mb-6 leading-relaxed">{{ $item->deskripsi }}</p>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('books.show', $item->id) }}" class="btn-skeleton-shimmer inline-flex items-center gap-2 bg-white/95 backdrop-blur-md text-black px-6 py-2.5 md:px-8 md:py-3.5 rounded-xl font-bold text-[10px] md:text-[12px] hover:bg-white transition-all shadow-xl active:scale-95">
                                LIHAT SEKARANG
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Controls (Minimal Dots) -->
        <div class="absolute bottom-6 left-8 md:left-16 flex gap-2 z-20">
            @foreach($featuredBooks as $k => $it)
            <div class="dot-btn h-1 w-4 md:w-6 bg-white/20 rounded-full cursor-pointer transition-all duration-300" data-index="{{ $k }}"></div>
            @endforeach
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.banner-slide');
        const dots = document.querySelectorAll('.dot-btn');
        const slideCount = slides.length;

        function updateSlider() {
            slides.forEach((slide, idx) => {
                if (idx === currentSlide) {
                    slide.classList.remove('opacity-0', 'pointer-events-none');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.add('opacity-0', 'pointer-events-none');
                    slide.classList.remove('opacity-100', 'z-10');
                }
            });

            dots.forEach((dot, idx) => {
                dot.classList.toggle('bg-white', idx === currentSlide);
                dot.classList.toggle('w-8', idx === currentSlide);
                dot.classList.toggle('bg-white/20', idx !== currentSlide);
                dot.classList.toggle('w-4', idx !== currentSlide);
            });
        }

        if (slideCount > 1) {
            setInterval(() => {
                currentSlide = (currentSlide + 1) % slideCount;
                updateSlider();
            }, 5000);
        }
        updateSlider();

        dots.forEach(dot => {
            dot.onclick = () => {
                currentSlide = parseInt(dot.dataset.index);
                updateSlider();
            }
        });
    </script>
    @endif

    <!-- Tabs & Search -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-gray-100 pb-1">
        <div class="flex-1 min-w-0 w-full" id="category-wrapper">
            <div id="category-scroll-container" class="overflow-x-auto snap-x scroll-smooth cursor-grab active:cursor-grabbing w-full pb-2 thin-scrollbar">
                <div class="flex items-center gap-6 md:gap-8 w-max px-1">
                    <a href="{{ route('katalog.index') }}" class="relative pb-2 snap-start {{ !request('category') ? 'text-black font-bold border-b-2 border-black' : 'text-gray-400 font-semibold hover:text-black transition' }} text-sm md:text-base tracking-normal select-none">Semua Koleksi</a>
                    @foreach($kategoris as $kat)
                        <a href="{{ route('katalog.index', ['category' => $kat->id]) }}" class="relative pb-2 snap-start {{ request('category') == $kat->id ? 'text-black font-bold border-b-2 border-black' : 'text-gray-400 font-semibold hover:text-black transition' }} text-sm md:text-base tracking-normal select-none">{{ $kat->nama_kategori }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="w-full md:w-96 relative mb-2">
            <form action="{{ route('katalog.index') }}" method="GET" class="relative group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-black transition text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis, atau penerbit..." 
                    class="w-full bg-white border-2 border-gray-100 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-bold text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black hover:border-gray-200 transition-all shadow-sm focus:shadow-md">
            </form>
        </div>
    </div>

    @if(!request('category'))

    <!-- Koleksi Terpopuler (Horizontal Scroll Style) -->
    <div class="mb-16">
        <div class="flex items-center justify-between mb-8 px-1">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 tracking-tight">Koleksi Dengan Bintang Tertinggi</h3>
            <a href="#koleksi" class="text-[10px] font-bold text-gray-400 hover:text-black transition uppercase tracking-widest flex items-center gap-2 group">
                Lihat Semua 
                <i class="fas fa-arrow-down text-[8px] group-hover:translate-y-1 transition-transform"></i>
            </a>
        </div>

        <div class="flex gap-6 md:gap-8 overflow-x-auto thin-scrollbar pb-6 px-1 snap-x scroll-smooth">
            @foreach($popularBooks as $book)
                <div class="group flex flex-col bg-transparent w-[140px] md:w-[180px] flex-shrink-0 snap-start relative">
                    <a href="{{ route('books.show', $book->id) }}" class="block">
                        <div class="aspect-[2/3] rounded-2xl overflow-hidden mb-3 bg-gray-50 relative shadow-sm border border-gray-100 transition-all duration-500 group-hover:shadow-xl group-hover:-translate-y-1 group-hover:border-gray-200">
                            @if($book->gambar)
                                <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover grayscale-[0.1] group-hover:grayscale-0 transition-all duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center p-4">
                                    <i class="fas fa-book-open text-gray-200 text-3xl"></i>
                                </div>
                            @endif
                        </div>
                    </a>
                    <div class="absolute top-2 right-2 z-20">
                        <form action="{{ route('koleksipribadi.toggle', $book->id) }}" method="POST" class="m-0">
                            @csrf
                            @php
                                $isFav = auth()->check() ? \App\Models\KoleksiPribadi::where('user_id', auth()->id())->where('book_id', $book->id)->exists() : false;
                            @endphp
                            <button type="submit" onclick="event.preventDefault(); this.parentElement.submit();" class="w-7 h-7 rounded-full bg-white/80 backdrop-blur-sm border border-gray-100 flex items-center justify-center hover:scale-110 hover:bg-white transition-all shadow-sm">
                                <i class="fas fa-heart text-[10px] {{ $isFav ? 'text-rose-500 drop-shadow-sm' : 'text-gray-300' }}"></i>
                            </button>
                        </form>
                    </div>
                    <div class="space-y-0.5 px-0.5">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex flex-wrap gap-1">
                                @foreach($book->kategoris->take(1) as $kat)
                                    <span class="{{ $getBadgeColor($kat->id) }} text-[7px] font-bold px-1.5 py-0.5 rounded uppercase tracking-tighter border shadow-sm">{{ $kat->nama_kategori }}</span>
                                @endforeach
                            </div>
                            <div class="flex items-center gap-1 text-[9px] font-bold text-yellow-500">
                                <i class="fas fa-star"></i> {{ number_format($book->ulasan_bukus_avg_rating ?? 0, 1) }}
                                <span class="text-[7px] text-gray-400">({{ $book->ulasan_bukus_count ?? 0 }})</span>
                            </div>
                        </div>
                        <h3 class="font-bold text-gray-900 group-hover:text-black text-xs md:text-sm line-clamp-1 leading-tight tracking-tight capitalize">{{ $book->judul }}</h3>
                        <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-tight truncate">{{ $book->penulis }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

<div class="mb-12 px-2">
    <div class="relative rounded-2xl overflow-hidden shadow-md h-[180px] md:h-[220px] group" id="static-banner-slider">
        @php
            $bannerPath = public_path('img/banner');
            $banners = [];
            if(file_exists($bannerPath)) {
                $bannerFiles = array_diff(scandir($bannerPath), ['.', '..']);
                foreach ($bannerFiles as $file) {
                    if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        $banners[] = asset('img/banner/' . $file);
                    }
                }
            }
        @endphp

        <!-- Slides Container -->
        <div class="relative w-full h-full">
            @foreach($banners as $index => $banner)
            <div class="promo-slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 pointer-events-none' }}" data-slide="{{ $index }}">
                <img 
                    src="{{ $banner }}" 
                    class="w-full h-full object-cover" 
                    alt="Banner Literasi {{ $index + 1 }}"
                />
            </div>
            @endforeach
        </div>

        <!-- Dots -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
            @foreach($banners as $index => $banner)
            <div class="promo-dot h-1.5 w-4 bg-white/40 hover:bg-white/80 rounded-full cursor-pointer transition-all duration-300 {{ $index === 0 ? 'bg-white w-8' : '' }}" data-slide="{{ $index }}"></div>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let currentPromo = 0;
        const promoSlides = document.querySelectorAll('.promo-slide');
        const promoDots = document.querySelectorAll('.promo-dot');
        const promoCount = promoSlides.length;

        if (promoCount > 1) {
            function updatePromoSlider() {
                promoSlides.forEach((slide, idx) => {
                    if (idx === currentPromo) {
                        slide.classList.remove('opacity-0', 'pointer-events-none');
                        slide.classList.add('opacity-100', 'z-10');
                    } else {
                        slide.classList.add('opacity-0', 'pointer-events-none');
                        slide.classList.remove('opacity-100', 'z-10');
                    }
                });

                promoDots.forEach((dot, idx) => {
                    dot.classList.toggle('bg-white', idx === currentPromo);
                    dot.classList.toggle('w-8', idx === currentPromo);
                    dot.classList.toggle('bg-white/40', idx !== currentPromo);
                    dot.classList.toggle('w-4', idx !== currentPromo);
                });
            }

            setInterval(() => {
                currentPromo = (currentPromo + 1) % promoCount;
                updatePromoSlider();
            }, 5000);

            promoDots.forEach(dot => {
                dot.onclick = () => {
                    currentPromo = parseInt(dot.dataset.slide);
                    updatePromoSlider();
                }
            });
        }
    });
</script>
    @endif

    @if(request('category'))
        <div class="mb-8">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight capitalize border-l-4 border-black pl-4">Kategori: {{ \App\Models\KategoriBuku::find(request('category'))?->nama_kategori ?? 'Koleksi' }}</h2>
            <p class="text-gray-500 text-sm mt-2 ml-5">Menampilkan hasil saringan khusus untuk kategori terpilih.</p>
        </div>
    @endif

    <!-- Grid Semua Koleksi (Full Paginated List) -->
    <div class="mb-14 pt-10 border-t border-gray-100" id="koleksi">
        <div class="flex items-center justify-between mb-8 px-1">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 tracking-tight">Semua Koleksi</h3>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                {{ $books->total() }} Buku
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-5 gap-6 md:gap-8">
            @forelse($books as $book)
            <div class="group flex flex-col bg-transparent relative">
                <a href="{{ route('books.show', $book->id) }}" class="block">
                    <div class="aspect-[2/3] rounded-xl overflow-hidden mb-3 bg-gray-50 relative shadow-sm border border-gray-100 transition-all duration-500 group-hover:shadow-md group-hover:border-gray-200">
                        @if($book->gambar)
                            <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover grayscale-[0.2] group-hover:grayscale-0 transition-all duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center p-4">
                                <i class="fas fa-book-open text-gray-200 text-2xl"></i>
                            </div>
                        @endif

                        @if($book->stok <= 0)
                        <div class="absolute inset-0 bg-white/80 backdrop-blur-[1px] flex items-center justify-center">
                            <span class="bg-gray-900 text-white text-[7px] font-bold px-2 py-0.5 rounded-full uppercase">Habis</span>
                        </div>
                        @endif
                    </div>
                </a>
                <div class="absolute top-2 right-2 z-20">
                    <form action="{{ route('koleksipribadi.toggle', $book->id) }}" method="POST" class="m-0">
                        @csrf
                        @php
                            $isFav2 = auth()->check() ? \App\Models\KoleksiPribadi::where('user_id', auth()->id())->where('book_id', $book->id)->exists() : false;
                        @endphp
                        <button type="submit" onclick="event.preventDefault(); this.parentElement.submit();" class="w-7 h-7 rounded-full bg-white/80 backdrop-blur-sm border border-gray-100 flex items-center justify-center hover:scale-110 hover:bg-white transition-all shadow-sm">
                            <i class="fas fa-heart text-[10px] {{ $isFav2 ? 'text-rose-500 drop-shadow-sm' : 'text-gray-300' }}"></i>
                        </button>
                    </form>
                </div>

                <div class="space-y-0.5 px-0.5">
                    <div class="flex items-center gap-1 mb-1">
                        <i class="fas fa-star text-yellow-400 text-[8px]"></i>
                        <span class="text-[9px] font-bold text-gray-700">{{ number_format($book->ulasan_bukus_avg_rating, 1) ?? '0.0' }}</span>
                        <span class="text-[8px] text-gray-400 font-medium">({{ $book->ulasan_bukus_count ?? 0 }})</span>
                    </div>
                    <div class="flex flex-wrap gap-1 mb-1.5">
                        @foreach($book->kategoris->take(2) as $kat)
                            <span class="{{ $getBadgeColor($kat->id) }} text-[6px] font-bold px-1 py-0.5 rounded uppercase tracking-tighter border shadow-sm">{{ $kat->nama_kategori }}</span>
                        @endforeach
                    </div>
                    <h3 class="font-bold text-gray-900 group-hover:text-black text-[11px] line-clamp-1 leading-none tracking-tight">{{ $book->judul }}</h3>
                    <p class="text-[8px] font-semibold text-gray-400 uppercase tracking-tight truncate mb-1">{{ $book->penulis }}</p>
                </div>
            </div>
            @empty
                <div class="col-span-full py-16 text-center bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-gray-400 font-bold text-[10px] tracking-widest uppercase">Belum ada koleksi yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination Links -->
        <div class="mt-12 flex items-center justify-center">
            {{ $books->fragment('koleksi')->links('partials.pagination') }}
        </div>
    </div>

        <!-- PROMO SECTION (Dual Banners) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
        <!-- Banner 1: Purple Gift -->
        <div class="relative rounded-[2rem] overflow-hidden group min-h-[220px] shadow-lg">
            <img src="{{ asset('img/promo/gift_box.png') }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            <div class="absolute inset-0 bg-indigo-600/40 mix-blend-multiply group-hover:bg-indigo-600/30 transition-colors"></div>
            <div class="relative z-10 p-8 h-full flex flex-col justify-center text-white">
                <div class="mb-4">
                    <span class="bg-rose-500 text-white text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest shadow-xl">Sale 20%</span>
                </div>
                <h3 class="text-3xl font-black leading-tight mb-2 truncate">Books Make <br> Great Gifts</h3>
                <p class="text-[11px] font-semibold text-gray-100/90 leading-relaxed max-w-[200px]">Why not send the gift of a book to family & friends.</p>
            </div>
        </div>

        <!-- Banner 2: Yellow Sale -->
        <div class="relative rounded-[2rem] overflow-hidden group min-h-[220px] shadow-lg">
            <img src="{{ asset('img/promo/book_sale.png') }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            <div class="absolute inset-0 bg-amber-500/20 mix-blend-multiply group-hover:bg-amber-500/10 transition-colors"></div>
            <div class="relative z-10 p-8 h-full flex flex-col justify-center text-right items-end">
                <div class="mb-2">
                    <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest bg-white/20 backdrop-blur-md px-2 py-1 rounded">Novels Every Day!</span>
                </div>
                <h3 class="text-3xl font-black leading-tight mb-1 text-gray-950">Sale 10% Off</h3>
                <p class="text-[11px] font-bold text-gray-800/80 mb-6 italic">It all begins with a great book!</p>
                <a href="#koleksi" class="btn-skeleton-shimmer bg-white text-black px-6 py-2.5 rounded-full font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 hover:bg-black hover:text-white transition shadow-xl active:scale-95">
                    LIHAT SEKARANG <i class="fas fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- LIBRARY FEATURES SECTION (Bento Style) - Rame & Modern -->
    <div class="mb-24 px-2">
        <!-- Header Section -->
        <div class="mb-16 max-w-3xl">
            <h2 class="text-4xl font-black text-gray-900 tracking-tighter leading-none mb-4">
                Tentang PustakaKU <br class="hidden md:block"> Untuk Pembaca Modern.
            </h2>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.3em] opacity-70">
                Berikan Laporan Jika Ada Kesalahan.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row items-center gap-12">
            <!-- Left Column: Illustration -->
            <img 
                src="{{ asset('img/vector/vector1.png') }}" 
                class="lg:w-1/2 w-full h-auto object-contain hover:scale-[1.02] transition-transform duration-500 cursor-pointer" 
                alt="Promo Literasi"
            />
            
            <!-- Right Column: Info Blocks -->
            <div class="lg:w-1/2 flex flex-col gap-6">

                <!-- 1. Community Block (Refined Size) -->
                <div class="rounded-[2rem] border border-gray-100 bg-gray-50 p-8 flex flex-col justify-between hover:bg-white hover:shadow-xl hover:shadow-black/[0.02] transition-all duration-500 group cursor-pointer relative overflow-hidden min-h-[240px]">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex flex-col">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] mb-3">Community Hub</p>
                            <h5 class="text-4xl font-black text-gray-900 tracking-tighter leading-none mb-2">1,250+</h5>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pengguna Aktif</span>
                            </div>
                        </div>
                        <div class="flex -space-x-3">
                            <div class="w-12 h-12 rounded-full border-4 border-white bg-gray-200 overflow-hidden shadow-lg">
                                <img src="{{ asset('img/pfp/pfp-m-1.png') }}" class="w-full h-full object-cover">
                            </div>
                            <div class="w-12 h-12 rounded-full border-4 border-white bg-gray-200 overflow-hidden shadow-lg">
                                <img src="{{ asset('img/pfp/pfp-f-1.png') }}" class="w-full h-full object-cover">
                            </div>
                            <div class="w-12 h-12 rounded-full border-4 border-white bg-black flex items-center justify-center text-[9px] text-white font-black shadow-lg">
                                +800
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-400 text-[10px] font-semibold leading-relaxed max-w-[240px] opacity-80 uppercase tracking-tight">
                        Bergabung dengan ribuan pembaca lainnya di ekosistem kami.
                    </p>
                    <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-black/[0.01] rounded-full group-hover:scale-125 transition-transform duration-1000"></div>
                </div>

                <!-- 2. Feature Grid (Refined) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="group rounded-[1.5rem] border border-gray-100 bg-gray-50 p-6 hover:bg-white hover:shadow-lg transition-all">
                        <div class="w-10 h-10 mb-4 flex items-center justify-center rounded-xl bg-black text-white shadow-md group-hover:scale-105 transition">
                            <i class="fas fa-bolt text-xs"></i>
                        </div>
                        <h5 class="font-bold text-gray-900 mb-1 uppercase text-[9px] tracking-widest">Verifikasi</h5>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Sistem Instan.</p>
                    </div>

                    <div class="group rounded-[1.5rem] border border-gray-100 bg-gray-50 p-6 hover:bg-white hover:shadow-lg transition-all">
                        <div class="w-10 h-10 mb-4 flex items-center justify-center rounded-xl bg-white text-black border border-gray-100 shadow-sm group-hover:scale-105 transition">
                            <i class="fas fa-shield-alt text-xs"></i>
                        </div>
                        <h5 class="font-bold text-gray-900 mb-1 uppercase text-[9px] tracking-widest">Proteksi</h5>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Data Aman.</p>
                    </div>
                </div>

                <!-- 3. Support Banner (Refined) -->
                <div class="relative overflow-hidden rounded-[2rem] bg-gray-900 p-8 flex items-center justify-between shadow-xl group cursor-pointer mt-auto">
                    <div class="relative z-10">
                        <h5 class="text-white font-bold text-xl tracking-tight mb-1">
                            Ada Kendala?
                        </h5>
                        <p class="text-gray-500 text-[9px] font-bold uppercase tracking-[0.2em]">
                            Bantuan teknis 24/7.
                        </p>
                    </div>
                    <div class="relative z-10 w-12 h-12 flex items-center justify-center bg-white text-black rounded-xl shadow-lg group-hover:rotate-6 transition">
                        <i class="fas fa-comment-dots text-sm"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endif

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .thin-scrollbar::-webkit-scrollbar { height: 4px; }
    .thin-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .thin-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
    .thin-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    .thin-scrollbar { scrollbar-width: thin; scrollbar-color: #e5e7eb transparent; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const slider = document.getElementById('category-scroll-container');
        let isDown = false;
        let startX;
        let scrollLeft;

        if (slider) {
            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                slider.classList.add('cursor-grabbing');
                slider.classList.remove('cursor-grab', 'scroll-smooth', 'snap-x');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });
            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('cursor-grabbing');
                slider.classList.add('cursor-grab', 'scroll-smooth', 'snap-x');
            });
            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('cursor-grabbing');
                slider.classList.add('cursor-grab', 'scroll-smooth', 'snap-x');
            });
            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 2; 
                slider.scrollLeft = scrollLeft - walk;
            });
            
            // Prevent link click when dragging
            let isDragging = false;
            slider.addEventListener('mousedown', () => isDragging = false);
            slider.addEventListener('mousemove', () => isDragging = true);
            
            const links = slider.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                    }
                });
            });
        }
    });
</script>

@endsection
