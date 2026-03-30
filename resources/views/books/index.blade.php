@extends('books.layout')

@section('content')
<div class="flex items-center justify-between mb-10">
    <h1 class="text-4xl font-extrabold tracking-tight">Data Buku</h1>
    <a href="{{ route('books.create') }}" class="bg-black text-white px-6 py-3 rounded-full font-semibold text-sm hover:bg-gray-800 transition shadow-md flex items-center gap-2">
        <i class="fas fa-plus text-[10px]"></i> Tambah Buku Baru
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400">ID</th>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400">Detail Buku</th>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400">Stok</th>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400">Penulis</th>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400">Tahun</th>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400">Harga</th>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($books as $index => $book)
                <tr class="hover:bg-gray-50 transition duration-300">
                    <td class="px-8 py-6 text-sm font-semibold text-gray-400">#{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-20 bg-gray-50 border border-gray-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0 relative">
                                @if($book->stok <= 0)
                                    <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-10"></div>
                                @endif
                                @if($book->gambar)
                                    <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover" alt="Cover">
                                @else
                                    <img src="https://api.dicebear.com/9.x/open-peeps/svg?seed={{ urlencode($book->judul) }}&size=64&face=smile" class="w-12 h-12 object-contain opacity-80" alt="Cover">
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-black mb-1 text-sm">{{ $book->judul }}</div>
                                <div class="text-[13px] text-gray-500 line-clamp-1 max-w-[200px]">{{ $book->deskripsi ?: 'Tidak ada deskripsi' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 font-semibold text-sm text-gray-900">
                        {{ $book->stok > 0 ? $book->stok . ' Unit' : 'Habis' }}
                    </td>
                    <td class="px-8 py-6 font-semibold text-sm text-gray-600">{{ $book->penulis }}</td>
                    <td class="px-8 py-6 font-bold text-sm text-black">{{ $book->tahun_terbit }}</td>
                    <td class="px-8 py-6 font-semibold text-sm text-black">
                        {{ $book->harga ? 'Rp ' . number_format($book->harga, 0, ',', '.') : 'Gratis' }}
                    </td>
                    <td class="px-8 py-6 text-center">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('books.show', $book->id) }}" class="text-gray-400 hover:text-black transition p-2" title="Lihat">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('books.edit', $book->id) }}" class="text-gray-400 hover:text-black transition p-2" title="Ubah">
                                <i class="fas fa-pen text-sm"></i>
                            </a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2 cursor-pointer" onclick="return confirm('Hapus data ini?')" title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-24 text-center text-gray-400">
                        <div class="mb-4">
                            <!-- Minimalist vector illustration -->
                            <img src="https://api.dicebear.com/9.x/open-peeps/svg?seed=Lucky&size=100&face=smile" class="w-32 mx-auto grayscale opacity-50">
                        </div>
                        <p class="font-bold text-sm tracking-widest uppercase">Tidak ada buku ditemukan di perpustakaan.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
