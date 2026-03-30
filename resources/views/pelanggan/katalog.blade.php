@extends('books.layout')

@section('content')
<!-- Recommendation Banner -->
@if($books->count() > 0)
<div class="mb-12 relative group rounded-[2.5rem] overflow-hidden bg-white border border-gray-100 shadow-sm h-[450px] md:h-[350px]">
    <div id="banner-slider" class="h-full relative flex transition-transform duration-700 ease-in-out">
        @foreach($books->take(3) as $key => $item)
        <div class="min-w-full h-full flex flex-col md:flex-row items-center justify-center px-10 md:px-20 gap-8 md:gap-16 bg-white py-12 md:py-0">
            <div class="flex-1 text-center md:text-left">
                <span class="inline-block bg-black text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4">Recommended Product</span>
                <h2 class="text-3xl md:text-5xl font-black text-black leading-tight mb-4 line-clamp-2 uppercase italic tracking-tighter">{{ $item->judul }}</h2>
                <p class="text-[13px] text-gray-500 font-medium mb-6 line-clamp-2 max-w-md mx-auto md:mx-0">{{ $item->deskripsi }}</p>
                <a href="{{ route('books.show', $item->id) }}" class="inline-flex items-center gap-2 bg-black text-white px-8 py-3.5 rounded-full font-bold text-[12px] hover:bg-gray-800 transition shadow-lg">
                    See Collection <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="flex-shrink-0 flex justify-center items-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-black/5 rounded-3xl blur-xl transform translate-y-4 scale-90"></div>
                    <img src="{{ Str::startsWith($item->gambar, 'http') ? $item->gambar : asset('img/' . $item->gambar) }}" class="h-48 md:h-64 w-36 md:w-48 object-cover rounded-3xl border border-gray-100 transform rotate-3 hover:rotate-0 transition-transform duration-500 z-10 relative">
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Dots Navigation -->
    <div class="absolute bottom-6 left-0 right-0 md:left-20 flex justify-center md:justify-start gap-2 h-1 overflow-visible">
        @foreach($books->take(3) as $k => $it)
        <button onclick="goToSlide({{ $k }})" class="dot-btn h-1.5 w-8 bg-gray-200 rounded-full transition-all duration-300" data-index="{{ $k }}"></button>
        @endforeach
    </div>
</div>

<script>
    let currentSlide = 0;
    const slidesCount = 3;
    const slider = document.getElementById('banner-slider');
    const dots = document.querySelectorAll('.dot-btn');

    function updateSlider() {
        slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach((dot, idx) => {
            if (idx === currentSlide) {
                dot.classList.remove('bg-gray-200', 'w-8');
                dot.classList.add('bg-black', 'w-12');
            } else {
                dot.classList.remove('bg-black', 'w-12');
                dot.classList.add('bg-gray-200', 'w-8');
            }
        });
    }

    function goToSlide(idx) {
        currentSlide = idx;
        updateSlider();
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slidesCount;
        updateSlider();
    }

    setInterval(nextSlide, 5000);
    updateSlider(); // Init
</script>
@endif

<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tight mb-2">Explore</h1>
        <p class="text-gray-500 text-[13px] font-medium">Discover our collection of amazing books.</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @forelse($books as $book)
        <!-- Minimalist Book Card -->
        <a href="{{ route('books.show', $book->id) }}" class="group flex flex-col h-full border border-gray-200 bg-white rounded-3xl p-6 hover:shadow-xl hover:border-black transition-all duration-300 transform hover:-translate-y-1">
            
            <!-- Book Cover Image Placeholder -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl h-56 mb-6 flex-shrink-0 flex flex-col items-center justify-center relative overflow-hidden transition-colors">
                
                <!-- Tag Tahun -->
                <div class="absolute top-4 right-4 bg-white border border-gray-200 shadow-sm text-[10px] font-bold px-3 py-1 rounded-full text-black z-10">
                    {{ $book->tahun_terbit }}
                </div>
                
                @if($book->stok <= 0)
                <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] flex items-center justify-center z-20">
                    <span class="bg-red-500 text-white font-bold px-4 py-1.5 rounded-full text-[10px] uppercase tracking-widest">Habis</span>
                </div>
                @endif

                @if($book->gambar)
                    <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" alt="Book Cover Art" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <img src="https://api.dicebear.com/9.x/open-peeps/svg?seed={{ urlencode($book->judul) }}&size=120&face=smile" alt="Book Outline" class="w-32 opacity-80 group-hover:opacity-100 transition-all duration-500 group-hover:scale-110">
                @endif
            </div>

            <div class="flex flex-col flex-grow">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                    <i class="fas fa-feather-alt text-[9px]"></i> {{ $book->penulis }}
                </div>
                
                <h3 class="font-extrabold text-lg text-black mb-3 leading-tight line-clamp-2">
                    {{ $book->judul }}
                </h3>
                
                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="font-bold text-[14px] text-black">
                        {{ $book->harga ? 'Rp ' . number_format($book->harga, 0, ',', '.') : 'Free' }}
                    </span>
                    <div class="w-8 h-8 rounded-full bg-gray-50 group-hover:bg-black group-hover:text-white text-gray-300 flex items-center justify-center transition-colors">
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </div>
                </div>
            </div>
            
        </a>
    @empty
        <!-- Kosong -->
        <div class="col-span-full py-24 text-center bg-white border border-gray-200 rounded-3xl">
            <img src="https://api.dicebear.com/9.x/open-peeps/svg?seed=Lucky&size=120&face=smile" class="w-32 mx-auto mb-4 grayscale opacity-40">
            <p class="font-bold text-xs tracking-widest uppercase text-gray-400">Belum ada buku di katalog.</p>
        </div>
    @endforelse
</div>
@endsection
