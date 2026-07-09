@extends('books.layout')

@section('content')

<div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6">
    <div class="flex-1">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Buku Favorit</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar buku yang telah Anda simpan ke dalam koleksi pribadi.</p>
    </div>

    <div class="w-full md:w-[480px]">
        <form action="{{ route('koleksipribadi.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-[10px] group-focus-within:text-black transition"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari di favorit..." 
                    class="w-full bg-white border border-gray-100 rounded-xl px-10 py-3 text-[12px] font-semibold text-black placeholder-gray-300 focus:ring-4 focus:ring-black/5 focus:border-black transition-all shadow-sm">
            </div>
            
            <div class="w-full sm:w-40">
                @include('partials.filter-dropdown', [
                    'name' => 'category',
                    'options' => $kategoris,
                    'selected' => request('category'),
                    'placeholder' => 'SEMUA FILTER',
                    'align' => 'right-0'
                ])
            </div>
        </form>
    </div>
</div>

<div class="mb-24 pt-10 border-t border-gray-100">
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6 md:gap-8">
        @forelse($koleksi as $book)
            <div class="group flex flex-col bg-transparent relative">
                <a href="{{ route('books.show', $book->id) }}" class="block">
                    <div class="aspect-[2/3] rounded-xl overflow-hidden mb-3 bg-gray-50 relative shadow-sm border border-gray-100 transition-all duration-500 group-hover:shadow-md group-hover:border-gray-200">
                        @if($book->gambar)
                            <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover grayscale-[0.1] group-hover:grayscale-0 transition-all duration-500">
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

                <!-- Custom absolute favorit form -->
                <div class="absolute top-2 right-2 z-20">
                    <form action="{{ route('koleksipribadi.toggle', $book->id) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" onclick="event.preventDefault(); this.parentElement.submit();" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm border border-gray-200 flex items-center justify-center shadow-sm hover:scale-110 transition active:scale-95 group-hover:bg-white text-[11px]">
                            <i class="fas fa-heart text-rose-500 drop-shadow-sm"></i>
                        </button>
                    </form>
                </div>

                <div class="space-y-0.5 px-0.5">
                    <div class="flex items-center gap-1 mb-1">
                        <i class="fas fa-star text-yellow-400 text-[8px]"></i>
                        <span class="text-[9px] font-bold text-gray-700">{{ number_format($book->ulasan_bukus_avg_rating, 1) ?? '0.0' }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 group-hover:text-black text-xs line-clamp-1 leading-tight tracking-tight">{{ $book->judul }}</h3>
                    <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-tight truncate">{{ $book->penulis }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-[2rem] border border-dashed border-gray-200">
                <i class="{{ request('search') || request('category') ? 'fas fa-search' : 'fas fa-heart-broken' }} text-gray-200 text-4xl mb-4"></i>
                <h3 class="text-gray-400 font-bold text-[10px] tracking-widest uppercase mb-1">
                    {{ request('search') || request('category') ? 'Hasil Tidak Ditemukan' : 'Belum Ada Favorit' }}
                </h3>
                <p class="text-gray-400 text-xs max-w-xs mx-auto">
                    {{ request('search') || request('category') 
                        ? 'Coba gunakan kata kunci lain atau hapus filter untuk melihat semua favorit Anda.' 
                        : 'Tambahkan buku ke favorit Anda untuk melihatnya di sini.' }}
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('koleksipribadi.index') }}" class="inline-block mt-6 text-[10px] font-bold text-black border-b border-black pb-0.5 hover:text-gray-500 hover:border-gray-500 transition uppercase tracking-widest">Hapus Semua Filter</a>
                @endif
            </div>
        @endforelse
    </div>
</div>

@endsection
