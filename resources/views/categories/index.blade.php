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
<div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tight mb-2">Manajemen Kategori</h1>
        <p class="text-gray-500 text-sm font-medium">Kelola kategori buku untuk pengelompokan koleksi yang lebih baik.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row items-center gap-4">
        <form action="{{ route('categories.index') }}" method="GET" class="relative group w-full sm:w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." 
                class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 pl-10 text-[11px] font-bold focus:outline-none focus:ring-4 focus:ring-black/5 focus:border-black transition-all shadow-sm">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-black transition"></i>
            
            @if(request('search'))
                <a href="{{ route('categories.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition">
                    <i class="fas fa-times-circle text-xs"></i>
                </a>
            @endif
        </form>

        <a href="{{ route('categories.create') }}" class="w-full sm:w-auto bg-black text-white hover:bg-gray-800 px-8 py-3.5 rounded-full font-bold text-[13px] transition flex items-center justify-center gap-2 shadow-xl active:scale-95">
            <i class="fas fa-plus text-[10px]"></i> Tambah Kategori
        </a>
    </div>
</div>


<div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <!-- <div class="border">
            @foreach($categories as $kat)
                <p>{{ $kat->nama_kategori }}</p>
            @endforeach
        </div> -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em]">No.</th>
                    <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em]">Nama Kategori</th>
                    <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($categories as $index => $category)
                <tr class="group hover:bg-gray-50/50 transition-colors">
                    <td class="px-8 py-6">
                        <span class="text-[13px] font-bold text-gray-400">{{ $index + 1 }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            <span class="{{ $getBadgeColor($category->id) }} text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest border shadow-sm">{{ $category->nama_kategori }}</span>
                            <span class="text-xs text-gray-300 font-medium">#{{ $category->id }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('categories.edit', $category->id) }}" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:text-black hover:bg-gray-100 transition shadow-sm border border-gray-100">
                                <i class="fas fa-edit text-[10px]"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="m-0 delete-category-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-rose-50 transition shadow-sm border border-gray-100 btn-delete-category">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <i class="fas fa-tags text-gray-100 text-5xl"></i>
                            <p class="text-gray-400 font-bold text-[11px] uppercase tracking-widest mt-2">Belum ada kategori buku.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8">
    {{ $categories->links('partials.pagination') }}
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll('.btn-delete-category');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-category-form');
            Swal.fire({
                title: 'Hapus Kategori?',
                html: 'Kategori yang dihapus <b>tidak dapat</b> dikembalikan!',
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
