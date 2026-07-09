@extends('books.layout')

@section('content')
@php
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
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Data Buku</h1>
            <p class="text-gray-500 text-sm">Kelola seluruh database koleksi buku perpustakaan.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <div class="w-full sm:w-auto">
                <form action="{{ route('books.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                    <div class="relative group w-full sm:w-56">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Judul, Penulis..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 pl-10 text-[11px] font-bold focus:outline-none focus:ring-4 focus:ring-black/5 focus:border-black transition shadow-sm">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-black transition"></i>
                    </div>
                    
                    <div class="w-full sm:w-40 shrink-0">
                        @include('partials.filter-dropdown', [
                            'name' => 'category',
                            'options' => $kategoris,
                            'selected' => request('category'),
                            'placeholder' => 'KATEGORI',
                            'align' => 'right-0'
                        ])
                    </div>

                    <div class="w-full sm:w-40 shrink-0">
                        @php
                            $stockStatusOptions = collect([
                                (object)['id' => 'tersedia', 'nama_kategori' => 'TERSEDIA'],
                                (object)['id' => 'menipis', 'nama_kategori' => 'MENIPIS'],
                                (object)['id' => 'habis', 'nama_kategori' => 'HABIS'],
                            ]);
                        @endphp
                        @include('partials.filter-dropdown', [
                            'name' => 'stock_status',
                            'options' => $stockStatusOptions,
                            'selected' => request('stock_status'),
                            'placeholder' => 'STOK',
                            'align' => 'right-0'
                        ])
                    </div>

                    @if(request()->anyFilled(['search', 'category', 'stock_status']))
                        <a href="{{ route('books.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 px-3 rounded-xl transition flex items-center justify-center" title="Reset Filter">
                            <i class="fas fa-undo text-[10px]"></i>
                        </a>
                    @endif
                </form>
            </div>
            <a href="{{ route('books.create') }}" class="w-full sm:w-auto bg-black text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-800 transition shadow-md flex items-center justify-center gap-2">
                <i class="fas fa-plus text-[10px]"></i> Tambah Baru
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

<!-- Tampilan Desktop (Tabel Layar Lebar) -->
<div class="hidden md:block bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden">
                <table class="min-w-[800px] w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">ID</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Info Buku</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Kategori</th>
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
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1 max-w-[150px]">
                                        @forelse($book->kategoris as $kat)
                                            <span class="{{ $getBadgeColor($kat->id) }} text-[8px] font-bold px-1.5 py-0.5 rounded uppercase tracking-tighter border shadow-sm">{{ $kat->nama_kategori }}</span>
                                        @empty
                                            <span class="text-gray-300 text-[10px] italic">Tanpa Kategori</span>
                                        @endforelse
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
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('books.show', $book->id) }}" class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 hover:text-black transition">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('books.edit', $book->id) }}" class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 hover:text-black transition">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </a>
                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="m-0 delete-book-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition btn-delete-book">
                                                <i class="fas fa-trash-alt text-[10px]"></i>
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
        
        <!-- Pagination Links Desktop -->
        <div class="mt-8">
            {{ $books->links('partials.pagination') }}
        </div>
    </div>
</div>

<!-- Tampilan Mobile (Android/Smartphone) -->
<div class="md:hidden space-y-4">
    @forelse ($books as $index => $book)
        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-16 rounded-xl bg-gray-50 overflow-hidden border border-gray-100 flex-shrink-0">
                    @if($book->gambar)
                        <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-200 text-xs"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 text-sm leading-tight max-w-[150px] truncate">{{ $book->judul }}</h4>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">{{ $book->penulis }}</p>
                </div>
            </div>
            
            <button onclick="openMobileModal('modal-book-{{ $book->id }}')" class="bg-black text-white hover:bg-gray-800 px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                Lihat Detail
            </button>
        </div>

        <!-- Modal Detail Mobile untuk Buku INI -->
        <div id="modal-book-{{ $book->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center opacity-0 transition-opacity duration-300">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeMobileModal('modal-book-{{ $book->id }}')"></div>
            
            <div class="bg-white w-[90%] max-w-sm rounded-[2rem] p-6 shadow-2xl relative z-10 transform scale-95 transition-transform duration-300">
                <button onclick="closeMobileModal('modal-book-{{ $book->id }}')" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center bg-gray-50 rounded-full text-gray-400 hover:text-black">
                    <i class="fas fa-times text-sm"></i>
                </button>
                
                <div class="flex flex-col items-center text-center mt-2 mb-6">
                    <div class="w-20 h-28 rounded-xl bg-gray-50 overflow-hidden border border-gray-100 mb-4 shadow-sm">
                        @if($book->gambar)
                            <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-gray-200"></i></div>
                        @endif
                    </div>
                    <h3 class="text-lg font-black tracking-tight text-gray-900 leading-tight">{{ $book->judul }}</h3>
                    <p class="text-xs text-gray-500 font-medium mt-1">{{ $book->penulis }}</p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-4 space-y-3 mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Genre</span>
                        <div class="flex flex-wrap gap-1 justify-end max-w-[150px]">
                            @forelse($book->kategoris as $kat)
                                <span class="{{ $getBadgeColor($kat->id) }} text-[8px] font-bold px-1.5 py-0.5 rounded uppercase tracking-tighter border shadow-sm">{{ $kat->nama_kategori }}</span>
                            @empty
                                <span class="text-gray-300 text-[10px] italic">Umum</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-sm border-t border-gray-100 pt-3">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Penerbit</span>
                        <span class="font-semibold text-gray-900">{{ $book->penerbit ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-t border-gray-100 pt-3">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tahun</span>
                        <span class="font-semibold text-gray-900">{{ $book->tahun_terbit }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-t border-gray-100 pt-3">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Stok</span>
                        @if($book->stok > 10)
                            <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-bold border border-green-100">{{ $book->stok }} Unit</span>
                        @elseif($book->stok > 0)
                            <span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-[10px] font-bold border border-orange-100">{{ $book->stok }} Unit</span>
                        @else
                            <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-[10px] font-bold border border-red-100">Habis</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <a href="{{ route('books.show', $book->id) }}" class="col-span-1 py-3 bg-gray-100 hover:bg-gray-200 text-black text-center text-[10px] font-bold uppercase tracking-widest rounded-xl transition flex items-center justify-center">
                        Lihat
                    </a>
                    <a href="{{ route('books.edit', $book->id) }}" class="col-span-1 py-3 bg-black hover:bg-gray-800 text-white text-center text-[10px] font-bold uppercase tracking-widest rounded-xl transition flex items-center justify-center">
                        Edit
                    </a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="col-span-1 m-0 delete-book-form h-full">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="w-full h-full py-3 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 text-center text-[10px] font-bold uppercase tracking-widest rounded-xl transition flex items-center justify-center btn-delete-book">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white border border-gray-100 rounded-3xl p-8 text-center text-gray-400 flex flex-col items-center">
            <i class="fas fa-book-open text-3xl mb-3 opacity-20"></i>
            <p class="text-xs font-bold tracking-widest uppercase">Pustaka Kosong.</p>
        </div>
    @endforelse

    <!-- Pagination Links Mobile -->
    <div class="pt-6 pb-10">
        {{ $books->links('partials.pagination') }}
    </div>
</div>

<script>
    function openMobileModal(id) {
        const modal = document.getElementById(id);
        const card = modal.querySelector('.scale-95');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animations
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }, 10);
    }

    function closeMobileModal(id) {
        const modal = document.getElementById(id);
        const card = modal.querySelector('.scale-100');
        
        modal.classList.add('opacity-0');
        if(card){
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll('.btn-delete-book');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-book-form');
                Swal.fire({
                    title: 'Hapus Buku?',
                    html: 'Buku ini akan dihapus secara <b>permanen</b>!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    });
</script>

@endsection
