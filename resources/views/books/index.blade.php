@extends('books.layout')

@section('content')
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Data Buku</h1>
            <p class="text-gray-500 text-sm">Kelola seluruh database koleksi buku perpustakaan.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <div class="w-full sm:w-64">
                <form action="{{ route('books.index') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Koleksi..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 pl-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition shadow-sm">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-black transition"></i>
                </form>
            </div>
            <a href="{{ route('books.create') }}" class="w-full sm:w-auto bg-black text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-800 transition shadow-md flex items-center justify-center gap-2">
                <i class="fas fa-plus text-[10px]"></i> Tambah Buku Baru
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-widest">Total Koleksi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $books->count() }} Judul</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-widest">Stok Menipis</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $books->where('stok', '<', 5)->where('stok', '>', 0)->count() }} Buku</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-widest">Stok Habis</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $books->where('stok', '<=', 0)->count() }} Buku</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden">
                <table class="min-w-[800px] w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">ID</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Info Buku</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Penerbit</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Status Stok</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Tahun</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 text-center">Opsi Kerja</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 italic-none">
                        @forelse ($books as $index => $book)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-6 text-xs font-semibold text-gray-300 whitespace-nowrap">#{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-14 rounded-lg bg-gray-50 overflow-hidden border border-gray-100 flex-shrink-0">
                                            @if($book->gambar)
                                                <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-200 text-xs"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm leading-tight">{{ $book->judul }}</h4>
                                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">{{ $book->penulis }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-sm font-bold text-gray-900 whitespace-nowrap">{{ $book->penerbit ?: '-' }}</td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @if($book->stok > 10)
                                        <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-bold border border-green-100">{{ $book->stok }} Unit</span>
                                    @elseif($book->stok > 0)
                                        <span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-[10px] font-bold border border-orange-100">{{ $book->stok }} Unit</span>
                                    @else
                                        <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-[10px] font-bold border border-red-100">Habis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 text-sm font-bold text-gray-900 whitespace-nowrap">{{ $book->tahun_terbit }}</td>
                                <td class="px-6 py-6 text-sm font-bold text-gray-900 whitespace-nowrap">
                                    {{ $book->harga ? 'Rp ' . number_format($book->harga, 0, ',', '.') : 'Gratis' }}
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('books.show', $book->id) }}" class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 hover:text-black transition">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('books.edit', $book->id) }}" class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 hover:text-black transition">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </a>
                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="m-0 inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus data ini?')" class="w-8 h-8 flex items-center justify-center bg-red-50 rounded-lg text-red-400 hover:text-red-600 transition">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center text-gray-400">
                                    <p class="text-xs font-bold tracking-widest uppercase">Pustaka Kosong.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
