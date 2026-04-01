@extends('books.layout')

@section('content')
<div class="mb-10">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
        {{ auth()->user()->role === 'admin' || auth()->user()->role === 'petugas' ? 'Data Peminjaman' : 'Koleksi Saya' }}
    </h1>
    <p class="text-gray-500 text-sm">
        {{ auth()->user()->role === 'admin' || auth()->user()->role === 'petugas' ? 'Manajemen seluruh catatan peminjaman buku.' : 'Catatan riwayat buku yang sedang atau telah Anda pinjam.' }}
    </p>
</div>

<div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-[800px] w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Peminjam</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Buku</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Tgl Pinjam</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Tgl Kembali</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($peminjamans as $peminjaman)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-6 font-bold text-gray-900 text-xs">
                            {{ $peminjaman->user->name ?? 'User' }}
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-4 min-w-[200px]">
                                <div class="w-10 h-14 rounded bg-gray-50 overflow-hidden flex-shrink-0 border border-gray-100">
                                    @if(isset($peminjaman->book->gambar) && $peminjaman->book->gambar)
                                        <img src="{{ Str::startsWith($peminjaman->book->gambar, 'http') ? $peminjaman->book->gambar : asset('img/' . $peminjaman->book->gambar) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-book text-gray-200 text-xs"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm leading-tight">{{ $peminjaman->book->judul ?? 'Buku Dihapus' }}</h4>
                                    <p class="text-[11px] text-gray-400 font-medium mt-1">{{ $peminjaman->book->penulis ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-xs font-semibold text-gray-400 whitespace-nowrap">
                            {{ $peminjaman->tanggal_peminjaman ? \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-6 text-xs font-semibold text-gray-400 whitespace-nowrap">
                            {{ $peminjaman->tanggal_pengembalian ? \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-6">
                            @if($peminjaman->status_peminjaman === 'Pinjam')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-600 px-3 py-1.5 rounded-full border border-amber-100 text-[10px] font-bold">
                                    <i class="fas fa-clock text-[8px]"></i> Dipinjam
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-600 px-3 py-1.5 rounded-full border border-green-100 text-[10px] font-bold">
                                    <i class="fas fa-check-circle text-[8px]"></i> Kembali
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-6 text-center">
                            @if($peminjaman->status_peminjaman === 'Pinjam')
                                <form action="{{ route('books.kembalikan', $peminjaman->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-black text-white px-4 py-2 rounded-lg text-[10px] font-bold hover:bg-gray-800 transition">Kembalikan</button>
                                </form>
                            @else
                                <span class="text-gray-300 text-[10px] font-bold uppercase tracking-widest">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-24 text-center text-gray-400">
                            <p class="text-xs font-bold tracking-widest uppercase">Belum ada data peminjaman.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
