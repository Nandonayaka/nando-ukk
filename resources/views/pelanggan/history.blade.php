@extends('books.layout')

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="mb-10 flex flex-col xl:flex-row xl:items-end justify-between gap-6">
    <div class="flex-1">
        <h1 class="text-3xl font-black tracking-tight text-gray-900">
            {{ auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas' ? 'Data Peminjaman' : 'Koleksi Saya' }}
        </h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1">
            {{ auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas'
                ? 'Manajemen seluruh catatan peminjaman buku.'
                : 'Riwayat buku yang sedang atau telah kamu pinjam.' }}
        </p>
    </div>

    <div class="w-full xl:w-auto flex flex-col md:flex-row items-center gap-3">
        @if($peminjamans->total() > 0)
        <a href="{{ route('history.exportPdf', request()->all()) }}"
            class="w-full md:w-auto flex-shrink-0 inline-flex items-center justify-center gap-2 bg-rose-500 hover:bg-rose-600 text-white px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-rose-200 group">
            <i class="fas fa-file-pdf group-hover:scale-110 transition-transform"></i>
            PDF
        </a>
        @endif

        <form action="{{ route('history.index') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-2">
            {{-- Search --}}
            <div class="relative w-full sm:w-64 group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-[10px] group-focus-within:text-black transition"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku/user..." 
                    class="w-full bg-white border border-gray-100 rounded-xl px-10 py-3 text-[11px] font-bold text-black placeholder-gray-300 focus:ring-4 focus:ring-black/5 focus:border-black transition-all shadow-sm">
            </div>

            {{-- Category Filter --}}
            <div class="w-full sm:w-44">
                @include('partials.filter-dropdown', [
                    'name' => 'category',
                    'options' => $kategoris,
                    'selected' => request('category'),
                    'placeholder' => 'KATEGORI',
                    'align' => 'right-0'
                ])
            </div>

            {{-- Status Filter --}}
            <div class="w-full sm:w-44">
                @php
                    $statusOptions = collect([
                        (object)['id' => 'Menunggu', 'nama_kategori' => 'MENUNGGU'],
                        (object)['id' => 'Pinjam', 'nama_kategori' => 'DIPINJAM'],
                        (object)['id' => 'Kembali', 'nama_kategori' => 'KEMBALI'],
                        (object)['id' => 'Ditolak', 'nama_kategori' => 'DITOLAK'],
                        (object)['id' => 'Dibatalkan', 'nama_kategori' => 'DIBATALKAN'],
                    ]);
                @endphp
                @include('partials.filter-dropdown', [
                    'name' => 'status',
                    'options' => $statusOptions,
                    'selected' => request('status'),
                    'placeholder' => 'STATUS',
                    'align' => 'right-0'
                ])
            </div>

            @if(request()->anyFilled(['search', 'category', 'status']))
                <a href="{{ route('history.index') }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-500 p-3 rounded-xl transition flex items-center justify-center" title="Reset Filter">
                    <i class="fas fa-undo text-[10px]"></i>
                </a>
            @endif
        </form>
    </div>
</div>

{{-- ===== STATS ROW ===== --}}
@php
    $totalPinjam   = $peminjamans->where('status_peminjaman','Pinjam')->count();
    $totalKembali  = $peminjamans->where('status_peminjaman','Kembali')->count();
    $totalMenunggu = $peminjamans->where('status_peminjaman','Menunggu')->count();
    $totalDenda    = $peminjamans->sum('denda');
    $dendaBelumLunas = $peminjamans->where('status_denda','Belum Lunas')->sum('denda');
@endphp
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex flex-col gap-1">
        <span class="text-[9px] font-black uppercase tracking-[0.15em] text-amber-400">Sedang Dipinjam</span>
        <span class="text-2xl font-black text-amber-600">{{ $totalPinjam }}</span>
    </div>
    <div class="bg-green-50 border border-green-100 rounded-2xl p-4 flex flex-col gap-1">
        <span class="text-[9px] font-black uppercase tracking-[0.15em] text-green-400">Sudah Kembali</span>
        <span class="text-2xl font-black text-green-600">{{ $totalKembali }}</span>
    </div>
    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 flex flex-col gap-1">
        <span class="text-[9px] font-black uppercase tracking-[0.15em] text-gray-400">Total Denda</span>
        <span class="text-lg font-black text-gray-700">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
    </div>
    <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4 flex flex-col gap-1">
        <span class="text-[9px] font-black uppercase tracking-[0.15em] text-rose-400">Denda Belum Lunas</span>
        <span class="text-lg font-black text-rose-600">Rp {{ number_format($dendaBelumLunas, 0, ',', '.') }}</span>
    </div>
</div>

{{-- ===== DESKTOP TABLE ===== --}}
<div class="hidden md:block bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Buku</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Peminjam</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Tgl Pinjam</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Batas</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Denda</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Status</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 text-center">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($peminjamans as $p)
                <tr class="hover:bg-gray-50/60 transition-all duration-150">
                    {{-- Buku --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-12 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100 bg-gray-50 shadow-sm">
                                @if(isset($p->book->gambar) && $p->book->gambar)
                                    <img src="{{ Str::startsWith($p->book->gambar,'http') ? $p->book->gambar : asset('img/'.$p->book->gambar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-book text-gray-200 text-[10px]"></i></div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-gray-900 text-xs leading-tight line-clamp-2 max-w-[160px]">{{ $p->book->judul ?? 'Buku Dihapus' }}</p>
                                <p class="text-[10px] text-gray-400 font-medium mt-0.5 truncate max-w-[160px]">{{ $p->book->penulis ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    {{-- Peminjam --}}
                    <td class="px-6 py-5">
                        <p class="font-bold text-gray-800 text-xs">{{ $p->user->name ?? 'User' }}</p>
                    </td>
                    {{-- Tgl Pinjam --}}
                    <td class="px-6 py-5 text-[11px] font-semibold text-gray-500 whitespace-nowrap">
                        {{ $p->tanggal_peminjaman ? \Carbon\Carbon::parse($p->tanggal_peminjaman)->format('d M Y') : '-' }}
                    </td>
                    {{-- Batas --}}
                    <td class="px-6 py-5 text-[11px] font-semibold whitespace-nowrap
                        {{ ($p->status_peminjaman === 'Pinjam' && $p->tanggal_jatuh_tempo && now()->gt($p->tanggal_jatuh_tempo)) ? 'text-rose-600 font-bold' : 'text-gray-500' }}">
                        {{ $p->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d M Y') : '-' }}
                        @if($p->status_peminjaman === 'Pinjam' && $p->tanggal_jatuh_tempo && now()->gt($p->tanggal_jatuh_tempo))
                            <span class="block text-[8px] text-rose-400 font-bold">Terlambat!</span>
                        @endif
                    </td>
                    {{-- Denda --}}
                    <td class="px-6 py-5">
                        @if($p->denda > 0)
                            <p class="text-xs font-black {{ $p->status_denda === 'Lunas' ? 'text-green-600' : 'text-rose-600' }}">
                                Rp {{ number_format($p->denda, 0, ',', '.') }}
                            </p>
                            <p class="text-[9px] font-bold uppercase tracking-wide {{ $p->status_denda === 'Lunas' ? 'text-green-400' : 'text-rose-400' }}">
                                {{ $p->status_denda }}
                            </p>
                        @else
                            <span class="text-[11px] text-gray-300 font-bold">—</span>
                        @endif
                    </td>
                    {{-- Status --}}
                    <td class="px-6 py-5">
                        @if($p->status_peminjaman === 'Pinjam')
                            <span class="inline-flex items-center gap-1.5 bg-orange-100 text-orange-600 px-2.5 py-1 rounded-full border border-orange-200 text-[9px] font-black uppercase tracking-wide">
                                <span class="w-1.5 h-1.5 bg-orange-600 rounded-full animate-pulse"></span>Dipinjam
                            </span>
                        @elseif($p->status_peminjaman === 'Menunggu')
                            <span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-600 px-2.5 py-1 rounded-full border border-yellow-200 text-[9px] font-black uppercase tracking-wide">
                                <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full animate-pulse"></span>Menunggu
                            </span>
                        @elseif($p->status_peminjaman === 'Kembali')
                            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-600 px-2.5 py-1 rounded-full border border-green-200 text-[9px] font-black uppercase tracking-wide">
                                <i class="fas fa-check text-[7px]"></i>Kembali
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 px-2.5 py-1 rounded-full border border-red-200 text-[9px] font-black uppercase tracking-wide">
                                <i class="fas fa-times text-[7px]"></i>{{ $p->status_peminjaman }}
                            </span>
                        @endif
                    </td>
                    {{-- Aksi --}}
                    <td class="px-6 py-5 text-center">
                        <button onclick="openDetailModal({{ $p->id }})"
                            class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-black hover:text-white text-gray-500 transition-all flex items-center justify-center mx-auto">
                            <i class="fas fa-eye text-[11px]"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-20 text-center">
                        <i class="fas fa-history text-4xl text-gray-100 mb-4 block"></i>
                        <p class="text-xs font-bold text-gray-300 uppercase tracking-widest">Belum ada data peminjaman</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-8">
        {{ $peminjamans->links('partials.pagination') }}
    </div>
</div>

{{-- ===== MOBILE CARDS ===== --}}
<div class="md:hidden space-y-3 mb-8">
    @forelse ($peminjamans as $p)
    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex items-center gap-3">
        <div class="w-12 h-16 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100 bg-gray-50 shadow-sm">
            @if(isset($p->book->gambar) && $p->book->gambar)
                <img src="{{ Str::startsWith($p->book->gambar,'http') ? $p->book->gambar : asset('img/'.$p->book->gambar) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center"><i class="fas fa-book text-gray-200 text-sm"></i></div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-gray-900 text-sm leading-tight truncate">{{ $p->book->judul ?? 'Buku Dihapus' }}</p>
            <p class="text-[10px] text-gray-400 font-semibold mt-0.5">{{ $p->book->penulis ?? '-' }}</p>
            <div class="mt-1.5 flex items-center gap-2">
                @if($p->status_peminjaman === 'Pinjam')
                    <span class="text-[9px] font-black bg-amber-100 text-amber-300 px-2 py-0.5 rounded-full uppercase tracking-wide">Dipinjam</span>
                @elseif($p->status_peminjaman === 'Menunggu')
                    <span class="text-[9px] font-black bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full uppercase tracking-wide">Menunggu</span>
                @else
                    <span class="text-[9px] font-black bg-green-100 text-green-600 px-2 py-0.5 rounded-full uppercase tracking-wide">Kembali</span>
                @endif
                @if($p->denda > 0 && $p->status_denda === 'Belum Lunas')
                    <span class="text-[9px] font-black bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full uppercase tracking-wide">Ada Denda</span>
                @endif
            </div>
        </div>
        <button onclick="openDetailModal({{ $p->id }})"
            class="flex-shrink-0 w-9 h-9 rounded-xl bg-black text-white flex items-center justify-center hover:bg-gray-800 transition">
            <i class="fas fa-chevron-right text-[11px]"></i>
        </button>
    </div>
    @empty
    <div class="bg-white border border-gray-100 rounded-3xl p-10 text-center flex flex-col items-center gap-3">
        <i class="fas fa-history text-3xl text-gray-200"></i>
        <p class="text-xs font-bold text-gray-300 uppercase tracking-widest">Belum ada riwayat</p>
    </div>
    @endforelse
    <div class="mt-8 pb-10">
        {{ $peminjamans->links('partials.pagination') }}
    </div>
</div>

{{-- ===== DETAIL MODAL ===== --}}
<div id="detail-modal" class="fixed inset-0 z-[200] hidden items-end sm:items-center justify-center" aria-modal="true">
    {{-- Backdrop --}}
    <div id="modal-backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeDetailModal()"></div>

    {{-- Card --}}
    <div id="modal-card" class="relative z-10 bg-white w-full sm:max-w-md rounded-t-[2rem] sm:rounded-[2rem] shadow-2xl translate-y-10 sm:translate-y-0 sm:scale-95 transition-all duration-300 max-h-[92vh] overflow-y-auto mx-0 sm:mx-4">

        {{-- Handle bar (mobile) --}}
        <div class="flex justify-center pt-3 pb-1 sm:hidden">
            <div class="w-10 h-1 bg-gray-200 rounded-full"></div>
        </div>

        {{-- Close button --}}
        <button onclick="closeDetailModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-400 hover:text-black transition z-20">
            <i class="fas fa-times text-xs"></i>
        </button>

        {{-- Book Cover Section --}}
        <div class="flex items-start gap-5 px-7 pt-6 pb-6 border-b border-gray-100">
            <div id="modal-cover" class="w-20 h-28 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100 shadow-lg bg-gray-50 flex items-center justify-center">
                <i class="fas fa-book text-gray-200 text-xl"></i>
            </div>
            <div class="flex-1 min-w-0 pt-1">
                <div id="modal-status-badge" class="inline-block text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full mb-2">—</div>
                <h3 id="modal-judul" class="text-base font-black text-gray-900 leading-tight mb-1">—</h3>
                <p id="modal-penulis" class="text-[11px] text-gray-400 font-semibold">—</p>
                <p id="modal-penerbit" class="text-[10px] text-gray-300 font-semibold mt-0.5">—</p>
            </div>
        </div>

        {{-- Info Rows --}}
        <div class="px-7 py-5 space-y-0 divide-y divide-gray-50">
            <div class="flex justify-between items-center py-3.5">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Peminjam</span>
                <span id="modal-peminjam" class="font-bold text-gray-800 text-[11px] text-right max-w-[60%]">—</span>
            </div>
            <div class="flex justify-between items-center py-3.5">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tgl Pinjam</span>
                <span id="modal-tgl-pinjam" class="font-semibold text-gray-700 text-[11px] text-right">—</span>
            </div>
            <div class="flex justify-between items-center py-3.5">
                <span class="text-[10px] font-bold text-rose-400 uppercase tracking-widest">Batas Kembali</span>
                <span id="modal-tgl-tempo" class="font-bold text-rose-500 text-[11px] text-right">—</span>
            </div>
            <div class="flex justify-between items-center py-3.5">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tgl Dikembalikan</span>
                <span id="modal-tgl-kembali" class="font-semibold text-gray-700 text-[11px] text-right">—</span>
            </div>
            <div id="modal-denda-row" class="flex justify-between items-center py-3.5">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Denda</span>
                <span id="modal-denda" class="font-black text-[11px] text-right">—</span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div id="modal-actions" class="px-7 pb-7 flex flex-col gap-2.5">
            {{-- filled by JS --}}
        </div>
    </div>
</div>

{{-- ===== JSON DATA (safe, no inline PHP in onclick) ===== --}}
<script id="peminjaman-data" type="application/json">
@php
    echo json_encode($peminjamans->map(function($p) {
        return [
            'id'           => $p->id,
            'peminjam'     => $p->user->name ?? 'User',
            'judul'        => $p->book->judul ?? 'Buku Dihapus',
            'penulis'      => $p->book->penulis ?? '-',
            'penerbit'     => $p->book->penerbit ?? '-',
            'gambar'       => isset($p->book->gambar) && $p->book->gambar
                                ? (Str::startsWith($p->book->gambar,'http') ? $p->book->gambar : asset('img/'.$p->book->gambar))
                                : null,
            'tgl_pinjam'   => $p->tanggal_peminjaman   ? \Carbon\Carbon::parse($p->tanggal_peminjaman)->format('d M Y, H:i')   : '-',
            'tgl_tempo'    => $p->tanggal_jatuh_tempo   ? \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d M Y, H:i')  : '-',
            'tgl_kembali'  => $p->tanggal_pengembalian  ? \Carbon\Carbon::parse($p->tanggal_pengembalian)->format('d M Y, H:i') : '-',
            'denda'        => (int)($p->denda ?? 0),
            'status_denda' => $p->status_denda ?? '-',
            'status'       => $p->status_peminjaman,
            'is_owner'     => auth()->id() === $p->user_id && auth()->user()->role === 'peminjam',
            'is_admin'     => auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas',
            'return_url'   => route('books.kembalikan', $p->id),
            'pay_url'      => route('books.bayar-denda', $p->id),
            'konfirmasi_url' => route('books.konfirmasi', $p->id),
            'batalkan_url' => route('books.batalkan', $p->id),
            'tolak_url' => route('books.tolak', $p->id),
        ];
    })->keyBy('id'));
@endphp
</script>

<form id="form-kembalikan" method="POST" style="display:none">@csrf</form>
<form id="form-bayar"      method="POST" style="display:none">@csrf</form>
<form id="form-konfirmasi" method="POST" style="display:none">@csrf</form>
<form id="form-batalkan" method="POST" style="display:none">@csrf</form>
<form id="form-tolak" method="POST" style="display:none">@csrf</form>

<script>
(function() {
    const raw = JSON.parse(document.getElementById('peminjaman-data').textContent);
    const map = raw; // keyed by id

    // ── Open Modal ──────────────────────────────────────────────
    window.openDetailModal = function(id) {
        const d = map[id];
        if (!d) return;

        // Cover
        const cover = document.getElementById('modal-cover');
        cover.innerHTML = d.gambar
            ? `<img src="${d.gambar}" class="w-full h-full object-cover">`
            : `<i class="fas fa-book text-gray-200 text-xl"></i>`;

        // Status badge
        const badge = document.getElementById('modal-status-badge');
        if (d.status === 'Pinjam') {
            badge.textContent = 'Sedang Dipinjam';
            badge.className = 'inline-block text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full mb-2 bg-amber-100 text-amber-700';
        } else if (d.status === 'Menunggu') {
            badge.textContent = 'Menunggu Konfirmasi';
            badge.className = 'inline-block text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full mb-2 bg-yellow-100 text-yellow-700';
        } else if (d.status === 'Kembali') {
            badge.textContent = '✓ Sudah Kembali';
            badge.className = 'inline-block text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full mb-2 bg-green-100 text-green-700';
        } else {
            badge.textContent = '✕ ' + d.status;
            badge.className = 'inline-block text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full mb-2 bg-red-100 text-red-700';
        }

        // Text fields
        setText('modal-judul',      d.judul);
        setText('modal-penulis',    d.penulis);
        setText('modal-penerbit',   d.penerbit !== '-' ? '-' + d.penerbit : '');
        setText('modal-peminjam',   d.peminjam);
        setText('modal-tgl-pinjam', d.tgl_pinjam);
        setText('modal-tgl-tempo',  d.tgl_tempo);
        setText('modal-tgl-kembali', d.tgl_kembali !== '-' ? d.tgl_kembali : '—');

        // Denda
        const dendaRow = document.getElementById('modal-denda-row');
        const dendaEl  = document.getElementById('modal-denda');
        if (d.denda > 0) {
            const fmt = 'Rp ' + fmtNum(d.denda) + ' · ' + d.status_denda;
            dendaEl.textContent = fmt;
            dendaEl.className = d.status_denda === 'Lunas'
                ? 'font-black text-[11px] text-right text-green-600'
                : 'font-black text-[11px] text-right text-rose-600';
            dendaRow.style.display = 'flex';
        } else {
            dendaRow.style.display = 'none';
        }

        // Action buttons
        const actions = document.getElementById('modal-actions');
        actions.innerHTML = '';

        if (d.status === 'Menunggu' && d.is_admin) {
            actions.innerHTML += btn('blue', 'fa-check', 'Konfirmasi Peminjaman', `confirmKonfirmasi(${d.id})`);
            actions.innerHTML += btn('rose', 'fa-times-circle', 'Tolak Permintaan', `confirmTolak(${d.id})`);
        } else if (d.status === 'Menunggu' && d.is_owner) {
            actions.innerHTML += btn('rose', 'fa-times', 'Batalkan Pinjaman', `confirmBatalkan(${d.id})`);
        } else if (d.status === 'Pinjam' && d.is_owner) {
            actions.innerHTML += btn('black', 'fa-undo', 'Kembalikan Buku', `confirmReturn(${d.id})`);
        } else if (d.status === 'Kembali' && d.denda > 0 && d.status_denda === 'Belum Lunas' && d.is_owner) {
            actions.innerHTML += btn('rose', 'fa-money-bill-wave', 'Bayar Denda · Rp ' + fmtNum(d.denda), `confirmPay(${d.id})`);
        } else if (d.status === 'Kembali') {
            actions.innerHTML += `
                <div class="w-full flex items-center justify-center gap-2 bg-green-50 text-green-600 py-3.5 rounded-xl text-[11px] font-bold border border-green-200">
                    <i class="fas fa-check-circle"></i> Peminjaman Selesai
                </div>`;
        }
        actions.innerHTML += btn('rose', 'fa-file-pdf', 'Cetak Bukti (PDF)', `exportSingleToPdf(${d.id})`);

        // Show
        const modal    = document.getElementById('detail-modal');
        const card     = document.getElementById('modal-card');
        const backdrop = document.getElementById('modal-backdrop');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            card.classList.remove('translate-y-10', 'sm:scale-95');
            card.classList.add('translate-y-0', 'sm:scale-100');
        });
    };

    window.closeDetailModal = function() {
        const modal    = document.getElementById('detail-modal');
        const card     = document.getElementById('modal-card');
        const backdrop = document.getElementById('modal-backdrop');
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        card.classList.remove('translate-y-0', 'sm:scale-100');
        card.classList.add('translate-y-10', 'sm:scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
    };

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetailModal(); });

    // ── Confirm Actions ─────────────────────────────────────────
    window.confirmKonfirmasi = function(id) {
        const d = map[id];
        Swal.fire({
            title: 'Konfirmasi Peminjaman?',
            html: `Konfirmasi persetujuan peminjaman buku <b>${d.judul}</b> oleh <b>${d.peminjam}</b>.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Konfirmasi!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) submitForm('form-konfirmasi', d.konfirmasi_url); });
    };

    window.confirmTolak = function(id) {
        const d = map[id];
        Swal.fire({
            title: 'Tolak Peminjaman?',
            html: `Apakah Anda yakin ingin menolak peminjaman buku <b>${d.judul}</b> oleh <b>${d.peminjam}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) submitForm('form-tolak', d.tolak_url); });
    };

    window.confirmBatalkan = function(id) {
        const d = map[id];
        Swal.fire({
            title: 'Batalkan Pinjaman?',
            html: `Apakah Anda yakin ingin membatalkan permintaan pinjam buku <b>${d.judul}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Kembali',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) submitForm('form-batalkan', d.batalkan_url); });
    };

    window.confirmReturn = function(id) {
        const d = map[id];
        Swal.fire({
            title: 'Kembalikan Buku?',
            html: `Anda akan mengembalikan buku <b>${d.judul}</b>.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kembalikan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) submitForm('form-kembalikan', d.return_url); });
    };

    window.confirmPay = function(id) {
        const d = map[id];
        Swal.fire({
            title: 'Bayar Denda?',
            html: `Denda sebesar <b>Rp ${fmtNum(d.denda)}</b> akan ditandai lunas.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) submitForm('form-bayar', d.pay_url); });
    };

    function submitForm(formId, action) {
        const f = document.getElementById(formId);
        f.action = action;
        f.submit();
    }

    // ── Export Single ────────────────────────────────────────────
    window.exportSingleToPdf = function(id) {
        window.location.href = `/history/${id}/pdf`;
    };

    // ── Export All ───────────────────────────────────────────────
    window.exportAllToPdf = function() {
        const urlParams = new URLSearchParams(window.location.search);
        window.location.href = `/history/export/pdf?` + urlParams.toString();
    };

    // ── Helpers ──────────────────────────────────────────────────
    function setText(id, val) { document.getElementById(id).textContent = val || '—'; }
    function fmtNum(n) { return new Intl.NumberFormat('id-ID').format(n); }
    function btn(color, icon, label, onclick) {
        const cls = {
            black:   'bg-black hover:bg-gray-800 text-white',
            rose:    'bg-rose-500 hover:bg-rose-600 text-white shadow-md shadow-rose-200',
            emerald: 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200',
            blue:    'bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-200',
        }[color] || 'bg-gray-100 text-gray-700';
        return `<button onclick="${onclick}" class="w-full flex items-center justify-center gap-2 ${cls} py-3.5 rounded-xl text-[11px] font-bold transition-all">
            <i class="fas ${icon}"></i> ${label}
        </button>`;
    }
})();
</script>
@endsection
