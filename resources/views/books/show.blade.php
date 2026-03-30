@extends('books.layout')

@section('content')
<div class="flex items-center justify-between mb-10">
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight mb-2">Detail Buku</h1>
            <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest bg-gray-100 inline-block px-3 py-1 rounded-full">ID: #{{ str_pad($book->id, 3, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>
    
    @php
        $backRoute = auth()->user()->role === 'admin' ? route('books.index') : route('katalog.index');
    @endphp
    <a href="{{ $backRoute }}" class="bg-white border border-gray-300 text-black hover:border-black px-6 py-3 rounded-full font-semibold text-[13px] transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-arrow-left text-[10px]"></i> Kembali
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-3xl flex flex-col md:flex-row overflow-hidden max-w-5xl shadow-sm">
    
    <!-- LEFT: Image Placeholder (Dicebear or Uploaded image) -->
    <div class="w-full md:w-5/12 bg-gray-50 p-12 flex flex-col justify-center items-center border-b md:border-b-0 md:border-r border-gray-200 relative overflow-hidden">
        @if($book->gambar)
            <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" alt="Book Illustration" class="w-full h-auto max-h-[400px] object-cover rounded-2xl shadow-xl hover:-translate-y-2 transition duration-500">
        @else
            <img src="https://api.dicebear.com/9.x/open-peeps/svg?seed={{ urlencode($book->judul) }}&size=300&face=smile" alt="Book Illustration" class="w-full max-w-[200px] md:max-w-xs hover:-translate-y-2 transition duration-500 drop-shadow-xl">
        @endif
    </div>

    <!-- RIGHT: Info -->
    <div class="w-full md:w-7/12 p-10 md:p-14 flex flex-col">

        <div class="mb-4">
            <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400">{{ $book->penulis }}</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-black tracking-tight leading-tight mt-1 mb-2">
                {{ $book->judul }}
            </h2>
        </div>
        
        <div class="flex items-center gap-4 mb-8">
            <div class="bg-gray-100 px-4 py-1.5 rounded-full inline-flex items-center gap-2">
                <i class="far fa-calendar-alt text-gray-500 text-[11px]"></i>
                <span class="text-[12px] font-bold text-gray-600">{{ $book->tahun_terbit }}</span>
            </div>
            
            @if($book->stok > 0)
                <div class="bg-green-50 text-green-700 px-4 py-1.5 rounded-full inline-flex items-center gap-2">
                    <i class="fas fa-check-circle text-[11px]"></i>
                    <span class="text-[12px] font-bold">Stok: {{ $book->stok }}</span>
                </div>
            @else
                <div class="bg-red-50 text-red-600 px-4 py-1.5 rounded-full inline-flex items-center gap-2">
                    <i class="fas fa-times-circle text-[11px]"></i>
                    <span class="text-[12px] font-bold">Habis</span>
                </div>
            @endif
        </div>

        <div class="mb-8 flex-1">
            <h3 class="text-[13px] font-extrabold uppercase text-gray-900 mb-3 tracking-widest">Sinopsis</h3>
            <p class="text-gray-500 text-[15px] font-medium leading-relaxed">
                {{ $book->deskripsi ?: 'Buku yang luar biasa. Harap hubungi pustakawan jika Anda butuh penjelasan lebih rici terkait dengan konten buku yang di maksud.' }}
            </p>
        </div>

        <div class="mt-auto flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-t border-gray-100 pt-8">
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Harga</span>
                <span class="font-bold text-3xl text-black leading-none">
                    {{ $book->harga ? 'Rp ' . number_format($book->harga, 0, ',', '.') : 'Gratis' }}
                </span>
            </div>

            @if(auth()->user() && auth()->user()->role === 'pelanggan')
                <form action="{{ route('books.beli', $book->id) }}" method="POST">
                    @csrf
                    @if($book->stok > 0)
                        <button class="bg-black text-white hover:bg-gray-800 font-bold text-[13px] px-8 py-4 rounded-full flex items-center justify-center gap-3 transition shadow-md w-full sm:w-auto">
                            <i class="fas fa-shopping-bag"></i> Beli Sekarang
                        </button>
                    @else
                        <button type="button" disabled class="bg-gray-200 text-gray-400 font-bold text-[13px] px-8 py-4 rounded-full flex items-center justify-center gap-3 w-full sm:w-auto cursor-not-allowed">
                            <i class="fas fa-shopping-bag"></i> Stok Habis
                        </button>
                    @endif
                </form>
            @endif
        </div>

    </div>
</div>
@endsection
