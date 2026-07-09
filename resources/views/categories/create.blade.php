@extends('books.layout')

@section('content')
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tight mb-2">Tambah Kategori</h1>
        <p class="text-gray-500 text-sm font-medium">Buat kategori baru untuk mempermudah pencarian buku.</p>
    </div>
    <a href="{{ route('categories.index') }}" class="bg-white border border-gray-300 text-black hover:border-black px-6 py-3 rounded-full font-semibold text-[13px] transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Daftar
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm max-w-2xl">
    <form action="{{ route('categories.store') }}" method="POST" class="p-8 md:p-12 space-y-6">
        @csrf
        <div class="space-y-4">
            <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Nama Kategori *</label>
            <input type="text" name="nama_kategori" required maxlength="12" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400" placeholder="e.g. Edukasi, Fiksi, Teknologi...">
            @error('nama_kategori')
                <p class="text-red-500 text-[11px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end gap-4 mt-6">
            <button type="submit" class="bg-black text-white px-10 py-3.5 hover:bg-gray-800 font-bold text-[13px] rounded-full transition shadow-md flex items-center gap-3">
                <i class="fas fa-check text-[10px]"></i> <span>Simpan Kategori</span>
            </button>
        </div>
    </form>
</div>
@endsection
