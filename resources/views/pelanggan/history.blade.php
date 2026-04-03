@extends('books.layout')

@section('content')
<div class="mb-10">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
        {{ auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas' ? 'Data Peminjaman' : 'Koleksi Saya' }}
    </h1>
    <p class="text-gray-500 text-sm">
        {{ auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas' ? 'Manajemen seluruh catatan peminjaman buku.' : 'Catatan riwayat buku yang sedang atau telah Anda pinjam.' }}
    </p>
</div>

<div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-[1000px] w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Peminjam</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Buku</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Waktu Pinjam</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Batas Kembali</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Tgl Kembali</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Denda</th>
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
                            <div class="flex items-center gap-4 min-w-[180px]">
                                <div class="w-10 h-14 rounded bg-gray-50 overflow-hidden flex-shrink-0 border border-gray-100">
                                    @if(isset($peminjaman->book->gambar) && $peminjaman->book->gambar)
                                        <img src="{{ Str::startsWith($peminjaman->book->gambar, 'http') ? $peminjaman->book->gambar : asset('img/' . $peminjaman->book->gambar) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-book text-gray-200 text-xs"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-[11px] leading-tight">{{ $peminjaman->book->judul ?? 'Buku Dihapus' }}</h4>
                                    <p class="text-[9px] text-gray-400 font-medium mt-1">{{ $peminjaman->book->penulis ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-[10px] font-semibold text-gray-500 whitespace-nowrap">
                            {{ $peminjaman->tanggal_peminjaman ? \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-6 py-6 text-[10px] font-semibold text-rose-500 whitespace-nowrap">
                            {{ $peminjaman->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo)->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-6 py-6 text-[10px] font-semibold text-gray-500 whitespace-nowrap">
                            {{ $peminjaman->tanggal_pengembalian ? \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-6 py-6">
                            @if($peminjaman->denda > 0)
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black {{ $peminjaman->status_denda === 'Lunas' ? 'text-green-600' : 'text-rose-600' }}">
                                        Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[8px] font-bold uppercase {{ $peminjaman->status_denda === 'Lunas' ? 'text-green-400' : 'text-rose-400' }}">
                                        {{ $peminjaman->status_denda }}
                                    </span>
                                </div>
                            @else
                                <span class="text-[10px] font-bold text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-6">
                            @if($peminjaman->status_peminjaman === 'Pinjam')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-600 px-3 py-1.5 rounded-full border border-amber-100 text-[9px] font-bold">
                                    <i class="fas fa-clock text-[7px]"></i> Dipinjam
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-600 px-3 py-1.5 rounded-full border border-green-100 text-[9px] font-bold">
                                    <i class="fas fa-check-circle text-[7px]"></i> Kembali
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-6 text-center">
                            @if($peminjaman->status_peminjaman === 'Pinjam')
                                @if(auth()->user()->role === 'peminjam' && auth()->id() === $peminjaman->user_id)
                                    <form action="{{ route('books.kembalikan', $peminjaman->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-black text-white px-4 py-2 rounded-lg text-[9px] font-bold hover:bg-gray-800 transition">Kembalikan</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-[9px] font-bold uppercase tracking-widest italic">Hanya Peminjam</span>
                                @endif
                            @else
                                @if($peminjaman->denda > 0 && $peminjaman->status_denda === 'Belum Lunas')
                                    @if(auth()->user()->role === 'peminjam' && auth()->id() === $peminjaman->user_id)
                                        <form action="{{ route('books.bayar-denda', $peminjaman->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-rose-600 text-white px-4 py-2 rounded-lg text-[9px] font-bold hover:bg-rose-700 transition">Bayar Denda</button>
                                        </form>
                                    @else
                                        <span class="text-rose-400 text-[9px] font-bold uppercase tracking-widest italic">Belum Bayar</span>
                                    @endif
                                @else
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="text-green-600 text-[9px] font-bold uppercase tracking-widest">Selesai</span>
                                        @if($peminjaman->denda > 0)
                                            <span class="text-[8px] text-green-500 font-bold">Terima Kasih</span>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-8 py-24 text-center text-gray-400">
                            <p class="text-xs font-bold tracking-widest uppercase">Belum ada data peminjaman.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
