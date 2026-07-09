@extends('books.layout')

@section('content')
<!-- LOADING SKELETON OVERLAY -->
<div id="detail-skeleton" class="fixed inset-0 z-[500] bg-white transition-opacity duration-500 overflow-hidden pointer-events-none">
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-10 pt-24 md:pt-32">
        <div class="w-32 h-4 animate-skeleton rounded-full mb-10 opacity-50"></div>
        <div class="flex flex-col md:flex-row gap-12">
            <div class="w-full md:w-80 lg:w-96 aspect-[3/4] animate-skeleton rounded-[2.5rem]"></div>
            <div class="flex-1 space-y-6">
                <div class="w-2/3 h-12 animate-skeleton rounded-2xl"></div>
                <div class="w-1/2 h-6 animate-skeleton rounded-xl opacity-60"></div>
                <div class="space-y-3 pt-8">
                    <div class="w-full h-4 animate-skeleton rounded-lg opacity-40"></div>
                    <div class="w-full h-4 animate-skeleton rounded-lg opacity-40"></div>
                    <div class="w-4/5 h-4 animate-skeleton rounded-lg opacity-40"></div>
                </div>
                <div class="pt-10 flex gap-4">
                    <div class="w-40 h-14 animate-skeleton rounded-2xl"></div>
                    <div class="w-40 h-14 animate-skeleton rounded-2xl"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        const skeleton = document.getElementById('detail-skeleton');
        if(skeleton) {
            skeleton.classList.add('opacity-0');
            setTimeout(() => skeleton.remove(), 500);
        }
    });
</script>

@php
    $backRoute = auth()->user()->role === 'administrator' ? route('books.index') : route('katalog.index');
    $getBadgeColor = function($id) {
        $colors = [
            'bg-blue-50 text-blue-600 border-blue-100',
            'bg-red-50 text-red-600 border-red-100',
            'bg-emerald-50 text-emerald-600 border-emerald-100',
            'bg-amber-50 text-amber-600 border-amber-100',
            'bg-sky-50 text-sky-600 border-sky-100',
            'bg-pink-50 text-pink-600 border-pink-100',
            'bg-indigo-50 text-indigo-600 border-indigo-100',
            'bg-teal-50 text-teal-600 border-teal-100',
            'bg-orange-50 text-orange-600 border-orange-100',
            'bg-cyan-50 text-cyan-600 border-cyan-100'
        ];
        return $colors[$id % count($colors)];
    };
@endphp

<div class="mb-10">
    <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-black transition text-xs font-bold uppercase tracking-widest mb-6">
        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Koleksi
    </a>

    <div class="flex flex-col md:flex-row justify-between gap-8 items-start">
        <div class="max-w-2xl">
            @if($book->kategoris->count() > 0)
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($book->kategoris as $kat)
                        <span class="{{ $getBadgeColor($kat->id) }} text-[8px] font-black px-2.5 py-1.5 rounded-lg uppercase tracking-widest shadow-sm border">{{ $kat->nama_kategori }}</span>
                    @endforeach
                </div>
            @endif
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
        <div class="bg-white border border-gray-200 p-3 rounded-3xl shadow-sm group relative">
            <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-gray-50 flex items-center justify-center">
                @if($book->gambar)
                    <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                @else
                    <i class="fas fa-book-open text-gray-100 text-6xl"></i>
                @endif
            </div>

            @if(auth()->user() && auth()->user()->role === 'peminjam')
                <form action="{{ route('koleksipribadi.toggle', $book->id) }}" method="POST" class="absolute top-6 right-6 z-20">
                    @csrf
                    @php
                        $isFavPage = \App\Models\KoleksiPribadi::where('user_id', auth()->id())->where('book_id', $book->id)->exists();
                    @endphp
                    <button type="submit" class="w-14 h-14 rounded-2xl bg-white/90 backdrop-blur-md shadow-xl flex items-center justify-center hover:bg-white hover:scale-105 transition-all group">
                        <i class="fas fa-heart text-xl {{ $isFavPage ? 'text-rose-500 animate-pulse' : 'text-gray-300 group-hover:text-rose-500' }}"></i>
                    </button>
                </form>
            @endif
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
                    <div class="flex gap-3">
                        @if($book->stok > 0)
                            <button onclick="openBorrowModal()" class="flex-1 bg-black text-white py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-3 shadow-xl">
                                <i class="fas fa-bookmark text-xs"></i> Pinjam Buku
                            </button>
                        @else
                            <button type="button" disabled class="flex-1 bg-gray-200 text-gray-400 py-4 rounded-2xl font-bold text-sm cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                        
                        <form action="{{ route('koleksipribadi.toggle', $book->id) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="w-14 h-14 rounded-2xl bg-white border border-gray-100 flex items-center justify-center shadow-lg">
                                <i class="fas fa-heart {{ $isFavPage ? 'text-rose-500' : 'text-gray-300' }}"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Borrow Modal -->
<div id="borrowModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeBorrowModal()"></div>
    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl transform transition-all scale-95 opacity-0 duration-300" id="modalContainer">
        <div class="p-8 md:p-12">
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-px w-8 bg-gray-200"></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Konfirmasi Peminjaman</span>
                </div>
                <h2 class="text-3xl font-bold text-black leading-tight mb-2">Pilih Waktu Kembali</h2>
                <p class="text-gray-400 text-xs font-medium">Buku: <span class="text-black font-bold">{{ $book->judul }}</span></p>
            </div>
            <form id="borrowForm" action="{{ route('books.pinjam', $book->id) }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-4">
                    <label for="durasi" class="text-[11px] font-bold text-gray-400 uppercase tracking-widest block">Durasi Peminjaman</label>
                    <x-duration-picker name="durasi_tipe" id="durasiSelect" />

                    <!-- Custom Date Picker (Hidden by default) -->
                    <div id="customDateContainer" class="hidden space-y-2 mt-4 animate-in fade-in slide-in-from-top-2 duration-300">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest block">Tentukan Tanggal Kembali</label>
                        <div class="relative group">
                            <i class="fas fa-calendar-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-black transition-colors z-10"></i>
                            <input type="text" id="customFlatpickr" 
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-12 py-5 text-sm font-bold text-black focus:bg-white focus:border-black focus:ring-4 focus:ring-black/5 transition-all outline-none"
                                placeholder="Pilih tanggal...">
                        </div>
                    </div>

                    <!-- Date Selection Preview -->
                    <div id="datePreview" class="hidden mt-4 animate-in fade-in zoom-in duration-300">
                        <div class="bg-black/5 rounded-2xl p-4 border border-black/10 flex items-center justify-between">
                            <i class="fas fa-clock text-black/20 text-xs"></i>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Rencana Kembali</p>
                                <p id="previewText" class="text-[11px] font-black text-black leading-none"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo_input" required>

                <div class="pt-4 flex flex-col gap-3">
                    <button id="confirmBorrowBtn" type="button" onclick="confirmBorrow()" disabled class="w-full bg-black text-white py-4 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-gray-800 transition active:scale-95 shadow-xl shadow-black/10 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
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

<!-- Reviews Section -->
<div class="mt-20 border-t border-gray-100 pt-20">
    <div class="flex items-center gap-3 mb-10">
        <div class="h-px w-8 bg-black"></div>
        <span class="text-[10px] font-bold text-black uppercase tracking-widest">Ulasan Literasi</span>
    </div>

    <!-- Top Area: Stats & Form side by side -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16 items-stretch">
        <!-- Stats Card -->
        <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 md:p-12 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-3xl font-bold text-black mb-6">Penilaian Koleksi</h2>
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 mb-6">
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-5xl font-black text-black">{{ number_format($book->ulasanBukus->avg('rating'), 1) ?: '0.0' }}</span>
                        <span class="text-gray-400 font-bold text-sm">/ 5.0</span>
                    </div>
                    <div class="flex gap-1 mb-4 text-orange-400">
                        @php $avg = $book->ulasanBukus->avg('rating') ?: 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($avg) ? 'fas' : 'far' }} fa-star text-sm"></i>
                        @endfor
                    </div>
                    <p class="text-gray-400 text-xs font-medium">Berdasarkan {{ $book->ulasanBukus->count() }} ulasan dari pembaca.</p>
                </div>
            </div>
            
            <p class="text-gray-500 text-sm leading-relaxed italic">
                "Ulasan Anda membantu pembaca lain menemukan buku yang tepat dan berkontribusi pada komunitas literasi kami."
            </p>
        </div>

        <!-- Review Form Card -->
        @if(auth()->user() && auth()->user()->role === 'peminjam')
            <div id="ratingForm" class="bg-white border border-gray-200 rounded-[2.5rem] p-8 md:p-12 shadow-sm">
                <h3 class="text-2xl font-bold text-black mb-8">{{ $userUlasan ? 'Perbarui Ulasan Anda' : 'Bagaimana Pendapat Anda?' }}</h3>
                
                <form id="ulasanForm" action="{{ route('books.ulasan', $book->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-3">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest block">Pilih Rating</label>
                        <div class="flex items-center gap-3" id="starContainer">
                            @for($i = 1; $i <= 5; $i++)
                                @php $isFilled = $userUlasan && $i <= $userUlasan->rating; @endphp
                                <button type="button" onclick="setRating({{ $i }})" class="group outline-none">
                                    <i class="{{ $isFilled ? 'fas text-orange-400' : 'far text-gray-200' }} fa-star text-3xl group-hover:text-orange-400 transition cursor-pointer star-icon" data-rating="{{ $i }}"></i>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="{{ $userUlasan ? $userUlasan->rating : '' }}" required>
                    </div>

                    <div class="space-y-3">
                        <label for="ulasan" class="text-[11px] font-bold text-gray-400 uppercase tracking-widest block">Ulasan Anda</label>
                        <textarea name="ulasan" id="ulasan" rows="3" required
                            class="w-full bg-gray-50 border border-gray-100 rounded-3xl p-6 text-sm font-medium text-gray-700 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/5 transition-all outline-none resize-none"
                            placeholder="Tulis ulasan singkat...">{{ $userUlasan ? $userUlasan->ulasan : '' }}</textarea>
                    </div>

                    <button type="button" onclick="confirmUlasan()" class="w-full bg-black text-white py-4 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-gray-800 transition active:scale-95 shadow-xl">
                        {{ $userUlasan ? 'Update Ulasan' : 'Simpan Ulasan' }}
                    </button>
                </form>
            </div>
        @else
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2.5rem] p-12 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-6">
                    <i class="fas fa-lock text-gray-300"></i>
                </div>
                <p class="text-gray-900 font-bold mb-1">Butuh Akses Peminjam</p>
                <p class="text-gray-400 text-xs">Hanya peminjam yang dapat memberikan ulasan pada koleksi kami.</p>
            </div>
        @endif
    </div>

    <!-- Bottom Area: Review List full width -->
    <div class="space-y-8 max-w-4xl">
        <div class="flex items-center gap-3 mb-8">
            <div class="h-px w-8 bg-gray-200"></div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Komentar Pembaca</span>
        </div>

        @forelse($book->ulasanBukus->sortByDesc('created_at') as $ulasan)
            <div class="group">
                <div class="flex items-start gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-100 group-hover:border-black transition duration-500">
                        @if($ulasan->user->profile_picture)
                            <img src="{{ asset('storage/' . $ulasan->user->profile_picture) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xs font-bold text-gray-400">{{ substr($ulasan->user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <div class="mb-2">
                            <p class="font-bold text-gray-900 text-sm flex items-center">
                                {{ $ulasan->user->name }}
                                @if(auth()->id() === $ulasan->user_id)
                                    <span class="ml-2 bg-gray-100 text-gray-400 text-[8px] px-1.5 py-0.5 rounded uppercase tracking-widest">Anda</span>
                                @endif
                                
                                @if(auth()->check() && (auth()->id() === $ulasan->user_id || auth()->user()->role === 'administrator'))
                                    <button type="button" onclick="confirmDeleteUlasan({{ $ulasan->id }})" class="ml-3 text-red-300 hover:text-red-500 transition-colors" title="Hapus Ulasan">
                                        <i class="fas fa-trash-alt text-[10px]"></i>
                                    </button>
                                    <form id="delete-ulasan-{{ $ulasan->id }}" action="{{ route('ulasan.destroy', $ulasan->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </p>
                            <div class="flex items-center gap-3 mt-1">
                                <div class="flex gap-0.5 text-orange-400 text-[9px]">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $ulasan->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>
                                <div class="w-1 h-1 bg-gray-200 rounded-full"></div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $ulasan->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed break-all whitespace-pre-wrap mt-5">{{ $ulasan->ulasan }}</p>
                    </div>
                </div>
            </div>
            @if(!$loop->last)
                <div class="h-px w-full bg-gray-50 my-8"></div>
            @endif
        @empty
            <div class="bg-gray-50 rounded-3xl p-12 text-center border-2 border-dashed border-gray-100">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-6">
                    <i class="far fa-comment-dots text-gray-200 text-2xl"></i>
                </div>
                <p class="text-gray-900 font-bold mb-1">Belum Ada Ulasan</p>
                <p class="text-gray-400 text-xs">Jadilah yang pertama untuk memberikan pendapat literasi Anda.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate max date (10 days from now)
        const maxDate = new Date();
        maxDate.setDate(maxDate.getDate() + 12);

        flatpickr("#customFlatpickr", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            maxDate: maxDate,
            locale: "id",
            onChange: function(selectedDates, dateStr) {
                document.getElementById('tanggal_jatuh_tempo_input').value = dateStr;
                
                // Show Preview
                const preview = document.getElementById('datePreview');
                const previewText = document.getElementById('previewText');
                preview.classList.remove('hidden');
                previewText.textContent = dateStr;
                
                document.getElementById('confirmBorrowBtn').disabled = false;
            }
        });
    });

    function handleDurationChange(value) {
        const customContainer = document.getElementById('customDateContainer');
        const confirmBtn = document.getElementById('confirmBorrowBtn');
        const preview = document.getElementById('datePreview');
        
        if (value === 'custom') {
            customContainer.classList.remove('hidden');
            preview.classList.add('hidden'); // Hide until date is picked
            document.getElementById('tanggal_jatuh_tempo_input').value = '';
            confirmBtn.disabled = true;
        } else {
            customContainer.classList.add('hidden');
            updateDurationDate(value);
        }
    }

    function updateDurationDate(days) {
        if (!days) return;
        const daysInt = parseInt(days);
        const date = new Date();
        date.setDate(date.getDate() + daysInt);
        
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        const formattedDate = `${year}-${month}-${day} ${hours}:${minutes}`;
        document.getElementById('tanggal_jatuh_tempo_input').value = formattedDate;
        
        // Show Preview
        const preview = document.getElementById('datePreview');
        const previewText = document.getElementById('previewText');
        preview.classList.remove('hidden');
        previewText.textContent = formattedDate;
        
        document.getElementById('confirmBorrowBtn').disabled = false;
    }

    function setRating(rating) {
        document.getElementById('ratingInput').value = rating;
        const stars = document.querySelectorAll('.star-icon');
        stars.forEach(star => {
            const starRating = parseInt(star.getAttribute('data-rating'));
            if (starRating <= rating) {
                star.classList.remove('far', 'text-gray-200');
                star.classList.add('fas', 'text-orange-400');
            } else {
                star.classList.remove('fas', 'text-orange-400');
                star.classList.add('far', 'text-gray-200');
            }
        });
    }

    function scrollToRatingForm() {
        document.getElementById('ratingForm').scrollIntoView({ behavior: 'smooth' });
    }

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
    function confirmBorrow() {
        Swal.fire({
            title: 'Konfirmasi Peminjaman',
            text: "Apakah Anda yakin ingin meminjam buku ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#000000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Pinjam!',
            cancelButtonText: 'Batal',
            borderRadius: '2xl'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('borrowForm').submit();
            }
        });
    }

    function confirmUlasan() {
        const rating = document.getElementById('ratingInput').value;
        const ulasan = document.getElementById('ulasan').value;

        if (!rating || !ulasan) {
            Swal.fire({
                title: 'Data Belum Lengkap',
                text: 'Mohon isi rating dan ulasan Anda.',
                icon: 'warning',
                confirmButtonColor: '#000000'
            });
            return;
        }

        Swal.fire({
            title: 'Simpan Ulasan?',
            text: "Ulasan Anda akan dipublikasikan.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#000000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('ulasanForm').submit();
            }
        });
    }

    function confirmDeleteUlasan(id) {
        Swal.fire({
            title: 'Hapus Ulasan?',
            text: "Ulasan yang dihapus tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-ulasan-' + id).submit();
            }
        });
    }
</script>
@endsection
