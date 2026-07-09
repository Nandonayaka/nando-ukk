@extends('books.layout')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Kotak Masuk</h1>
            <p class="text-sm text-gray-400 font-semibold mt-1">
                {{ $messages->total() }} notifikasi
                @if(auth()->user()->unreadInboxCount() > 0)
                    · <span class="text-black">{{ auth()->user()->unreadInboxCount() }} belum dibaca</span>
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if($messages->total() > 0)
                <!-- Tandai Semua Dibaca -->
                <form action="{{ route('inbox.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-black px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all border border-gray-100">
                        <i class="fas fa-check-double text-[9px]"></i>
                        Baca Semua
                    </button>
                </form>

                <!-- Hapus Semua -->
                <form id="form-clear-all" action="{{ route('inbox.clearAll') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmClearAll()" class="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all border border-red-100">
                        <i class="fas fa-trash-alt text-[9px]"></i>
                        Hapus Semua
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Message List -->
    <div class="space-y-3">
        @forelse($messages as $msg)
            @php
                $colorMap = [
                    'blue'   => ['bg' => 'bg-blue-50', 'border' => 'border-blue-100', 'icon_bg' => 'bg-blue-100', 'icon_text' => 'text-blue-600', 'badge' => 'bg-blue-100 text-blue-600', 'title' => 'text-gray-900', 'msg' => 'text-gray-500', 'time' => 'text-gray-300'],
                    'red'    => ['bg' => 'bg-red-50', 'border' => 'border-red-100', 'icon_bg' => 'bg-red-100', 'icon_text' => 'text-red-500', 'badge' => 'bg-red-100 text-red-600', 'title' => 'text-gray-900', 'msg' => 'text-gray-500', 'time' => 'text-gray-300'],
                    'green'  => ['bg' => 'bg-green-50', 'border' => 'border-green-100', 'icon_bg' => 'bg-green-100', 'icon_text' => 'text-green-600', 'badge' => 'bg-green-100 text-green-600', 'title' => 'text-gray-900', 'msg' => 'text-gray-500', 'time' => 'text-gray-300'],
                    'purple' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-100', 'icon_bg' => 'bg-purple-100', 'icon_text' => 'text-purple-600', 'badge' => 'bg-purple-100 text-purple-600', 'title' => 'text-gray-900', 'msg' => 'text-gray-500', 'time' => 'text-gray-300'],
                    'yellow' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-100', 'icon_bg' => 'bg-yellow-100', 'icon_text' => 'text-yellow-600', 'badge' => 'bg-yellow-100 text-yellow-600', 'title' => 'text-gray-900', 'msg' => 'text-gray-500', 'time' => 'text-gray-300'],
                    'amber' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'icon_bg' => 'bg-amber-100', 'icon_text' => 'text-amber-600', 'badge' => 'bg-amber-100 text-amber-600', 'title' => 'text-gray-900', 'msg' => 'text-gray-500', 'time' => 'text-gray-300'],
                ];
                $c = $colorMap[$msg->color] ?? $colorMap['blue'];
                $typeLabels = [
                    'pinjam' => 'Peminjaman',
                    'denda' => 'Denda',
                    'bayar_denda' => 'Pembayaran',
                    'gacha' => 'Gacha',
                ];
            @endphp
            <div class="group relative {{ !$msg->is_read ? $c['bg'] : 'bg-white' }} border {{ !$msg->is_read ? $c['border'] : 'border-gray-100' }} rounded-2xl p-5 transition-all duration-300 hover:shadow-lg hover:shadow-black/[0.03] {{ !$msg->is_read ? '' : 'opacity-70' }}">
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="w-11 h-11 rounded-xl {{ $c['icon_bg'] }} {{ $c['icon_text'] }} flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="{{ $msg->icon ?? 'fas fa-bell' }} text-sm"></i>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="text-[8px] font-black uppercase tracking-[0.15em] px-2 py-0.5 rounded-md {{ $c['badge'] }}">{{ $typeLabels[$msg->type] ?? 'Info' }}</span>
                            @if(!$msg->is_read)
                                <span class="w-2 h-2 rounded-full bg-black animate-pulse"></span>
                            @endif
                        </div>
                        <h4 class="font-bold {{ $c['title'] }} text-sm tracking-tight leading-snug ">{{ $msg->title }}</h4>
                        <p class="text-xs {{ $c['msg'] }} mt-1 leading-relaxed">{{ $msg->message }}</p>
                        <p class="text-[10px] {{ $c['time'] }} font-bold mt-2 uppercase tracking-widest">
                            <i class="far fa-clock mr-1"></i>{{ $msg->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                        @if(!$msg->is_read)
                        <form action="{{ route('inbox.read', $msg->id) }}" method="POST">
                            @csrf
                            <button type="submit" title="Tandai dibaca" class="w-8 h-8 rounded-lg bg-white hover:bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 hover:text-black transition shadow-sm">
                                <i class="fas fa-check text-[10px]"></i>
                            </button>
                        </form>
                        @endif
                        <form id="form-delete-{{ $msg->id }}" action="{{ route('inbox.destroy', $msg->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete('form-delete-{{ $msg->id }}')" title="Hapus" class="w-8 h-8 rounded-lg bg-white hover:bg-red-50 border border-gray-100 flex items-center justify-center text-gray-300 hover:text-red-500 transition shadow-sm">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-24 text-center">
                <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-6 border border-gray-100">
                    <i class="fas fa-inbox text-gray-200 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-300 tracking-tight mb-2">Kotak Masuk Kosong</h3>
                <p class="text-xs text-gray-300 font-semibold max-w-xs mx-auto">Semua notifikasi Anda akan muncul di sini. Pinjam buku atau main Gacha untuk mendapat notifikasi!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($messages->hasPages())
    <div class="mt-10">
        {{ $messages->links('partials.pagination') }}
    </div>
    @endif
</div>

<script>
    function confirmClearAll() {
        Swal.fire({
            title: 'Hapus Semua Notifikasi?',
            text: 'Semua pesan di kotak masuk akan dihapus permanen dan tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Semua',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-clear-all').submit();
            }
        });
    }

    function confirmDelete(formId) {
        Swal.fire({
            title: 'Hapus Notifikasi?',
            text: 'Pesan ini akan dihapus dari kotak masuk.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endsection
