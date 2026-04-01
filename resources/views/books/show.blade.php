@extends('books.layout')

@section('content')
@php
    $backRoute = auth()->user()->role === 'admin' ? route('books.index') : route('katalog.index');
@endphp

<div class="mb-10">
    <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-black transition text-xs font-bold uppercase tracking-widest mb-6">
        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Koleksi
    </a>

    <div class="flex flex-col md:flex-row justify-between gap-8 items-start">
        <div class="max-w-2xl">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-px w-8 bg-gray-200"></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $book->penulis }}</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">{{ $book->judul }}</h1>
        </div>
        
        <div class="bg-gray-50 border border-gray-100 px-6 py-4 rounded-2xl flex items-center gap-4">
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-400">
                <i class="fas fa-boxes text-xs"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Status Stok</p>
                @if($book->stok > 0)
                    <p class="text-lg font-bold text-green-600 leading-none">{{ $book->stok }} Tersedia</p>
                @else
                    <p class="text-lg font-bold text-red-500 leading-none">Habis</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-12 gap-12">
    <!-- Cover Area -->
    <div class="md:col-span-5 lg:col-span-4">
        <div class="bg-white border border-gray-200 p-3 rounded-3xl shadow-sm group">
            <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-gray-50 flex items-center justify-center">
                @if($book->gambar)
                    <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                @else
                    <i class="fas fa-book-open text-gray-100 text-6xl"></i>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Area -->
    <div class="md:col-span-7 lg:col-span-8 flex flex-col py-2">
        <div class="space-y-10 flex-grow">
            <div>
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-align-left text-[10px] text-gray-300"></i> Sinopsis Koleksi
                </h3>
                <p class="text-gray-600 leading-relaxed text-lg">
                    {{ $book->deskripsi ?: 'Buku ini merupakan bagian dari koleksi literasi kami yang berharga. Menyajikan wawasan mendalam dan perspektif yang unik bagi para pembacanya.' }}
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-8 pt-8 border-t border-gray-100">
                <div>
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Penulis</h4>
                    <p class="font-bold text-gray-900">{{ $book->penulis }}</p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tahun Rilis</h4>
                    <p class="font-bold text-gray-900">{{ $book->tahun_terbit }}</p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Penerbit</h4>
                    <p class="font-bold text-gray-900">{{ $book->penerbit ?: 'Penerbit Umum' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-12 p-8 bg-gray-900 rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl mb-20 lg:mb-0">
            <div class="text-center sm:text-left">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status Ketersediaan</p>
                <p class="text-3xl font-bold text-white">
                    {{ $book->stok > 0 ? 'Tersedia' : 'Kosong' }}
                </p>
            </div>

            @if(auth()->user() && auth()->user()->role === 'peminjam')
                <div class="hidden sm:block">
                    <form action="{{ route('books.pinjam', $book->id) }}" method="POST">
                        @csrf
                        @if($book->stok > 0)
                            <button class="bg-white text-black px-10 py-4 rounded-xl font-bold text-sm hover:bg-gray-100 transition shadow-lg flex items-center gap-3">
                                <i class="fas fa-bookmark text-xs"></i> Pinjam Koleksi
                            </button>
                        @else
                            <button type="button" disabled class="bg-gray-800 text-gray-500 px-10 py-4 rounded-xl font-bold text-sm cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </form>
                </div>
                
                <!-- Mobile Fixed Action Bar -->
                <div class="sm:hidden fixed bottom-[72px] left-0 right-0 p-4 bg-white/80 backdrop-blur-md border-t border-gray-100 z-40">
                    <form action="{{ route('books.pinjam', $book->id) }}" method="POST">
                        @csrf
                        @if($book->stok > 0)
                            <button class="w-full bg-black text-white py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-3 shadow-xl">
                                <i class="fas fa-bookmark text-xs"></i> Pinjam Buku
                            </button>
                        @else
                            <button type="button" disabled class="w-full bg-gray-200 text-gray-400 py-4 rounded-2xl font-bold text-sm cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
