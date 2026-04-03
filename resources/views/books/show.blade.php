@extends('books.layout')

@section('content')
@php
    $backRoute = auth()->user()->role === 'administrator' ? route('books.index') : route('katalog.index');
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
                    @if($book->stok > 0)
                        <button onclick="openBorrowModal()" class="bg-white text-black px-10 py-4 rounded-xl font-bold text-sm hover:bg-gray-100 transition shadow-lg flex items-center gap-3">
                            <i class="fas fa-bookmark text-xs"></i> Pinjam Koleksi
                        </button>
                    @else
                        <button type="button" disabled class="bg-gray-800 text-gray-500 px-10 py-4 rounded-xl font-bold text-sm cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                </div>
                
                <!-- Mobile Fixed Action Bar -->
                <div class="sm:hidden fixed bottom-[72px] left-0 right-0 p-4 bg-white/80 backdrop-blur-md border-t border-gray-100 z-40">
                    @if($book->stok > 0)
                        <button onclick="openBorrowModal()" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-3 shadow-xl">
                            <i class="fas fa-bookmark text-xs"></i> Pinjam Buku
                        </button>
                    @else
                        <button type="button" disabled class="w-full bg-gray-200 text-gray-400 py-4 rounded-2xl font-bold text-sm cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Borrow Modal -->
<div id="borrowModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeBorrowModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="modalContainer">
        <div class="p-8 md:p-12">
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-px w-8 bg-gray-200"></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Konfirmasi Peminjaman</span>
                </div>
                <h2 class="text-3xl font-bold text-black leading-tight mb-2">Pilih Waktu Kembali</h2>
                <p class="text-gray-400 text-xs font-medium">Buku: <span class="text-black font-bold">{{ $book->judul }}</span></p>
            </div>

            <form action="{{ route('books.pinjam', $book->id) }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="tanggal_jatuh_tempo" class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Kapan Anda Akan Mengembalikan?</label>
                    <div class="relative group">
                        <i class="fas fa-calendar-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-black transition-colors z-10"></i>
                        <input type="text" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" required
                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-12 py-4 text-sm font-bold text-black focus:border-black focus:ring-4 focus:ring-black/5 transition-all outline-none"
                            placeholder="Klik untuk pilih waktu...">
                    </div>
                </div>

                <div class="pt-4 flex flex-col gap-3">
                    <button id="confirmBorrowBtn" type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-gray-800 transition active:scale-95 shadow-lg shadow-black/10">
                        Konfirmasi Peminjaman
                    </button>
                    <button type="button" onclick="closeBorrowModal()" class="w-full bg-white text-gray-400 py-3 rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:text-black transition">
                        Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let fp;

    document.addEventListener('DOMContentLoaded', function() {
        fp = flatpickr("#tanggal_jatuh_tempo", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "d/m/y H:i",
            minDate: "today",
            locale: "id",
            disableMobile: "true",
            onReady: function(selectedDates, dateStr, instance) {
                // Ensure alt input looks like our design
                if(instance.altInput) {
                    instance.altInput.classList.add('w-full', 'bg-gray-50', 'border', 'border-gray-100', 'rounded-2xl', 'px-12', 'py-4', 'text-sm', 'font-bold', 'text-black', 'focus:border-black', 'focus:ring-4', 'focus:ring-black/5', 'transition-all', 'outline-none');
                    instance.altInput.placeholder = "Pilih Hari & Jam Kembali";
                    instance.input.style.display = "none";
                }
            }
        });
    });

    function openBorrowModal() {
        const modal = document.getElementById('borrowModal');
        const container = document.getElementById('modalContainer');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeBorrowModal() {
        const modal = document.getElementById('borrowModal');
        const container = document.getElementById('modalContainer');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
</script>
@endsection
