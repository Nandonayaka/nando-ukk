@extends('books.layout')

@section('content')
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tight mb-2">Ubah Data</h1>
        <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest bg-gray-100 inline-block px-3 py-1 rounded-full">ID: #{{ str_pad($book->id, 3, '0', STR_PAD_LEFT) }}</p>
    </div>
    <a href="{{ route('books.index') }}" class="bg-white border border-gray-300 text-black hover:border-black px-6 py-3 rounded-full font-semibold text-[13px] transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-arrow-left text-[10px]"></i> Batalkan Perubahan
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm max-w-4xl">
    @if ($errors->any())
        <div class="bg-red-50 text-red-600 px-8 py-5 border-b border-red-100 font-bold uppercase text-[11px] tracking-widest flex gap-3">
            <i class="fas fa-info-circle text-sm"></i>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Judul Buku *</label>
                <input type="text" name="judul" value="{{ old('judul', $book->judul) }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400">
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Nama Penulis *</label>
                <input type="text" name="penulis" value="{{ old('penulis', $book->penulis) }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400">
            </div>
            
            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Tahun Terbit *</label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit) }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400 appearance-none">
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $book->harga) }}" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400 appearance-none">
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Stok (Jumlah) *</label>
                <input type="number" name="stok" value="{{ old('stok', $book->stok) }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400 appearance-none">
            </div>
        </div>

        <div class="space-y-2 pt-2">
            <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Deskripsi / Sinopsis</label>
            <textarea name="deskripsi" rows="4" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400 resize-none">{{ old('deskripsi', $book->deskripsi) }}</textarea>
        </div>

        <div class="space-y-2 pt-2">
            <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Gambar Sampul</label>
            
            @if($book->gambar)
            <div class="mb-4 flex items-center gap-4">
                <img src="{{ Str::startsWith($book->gambar, 'http') ? $book->gambar : asset('img/' . $book->gambar) }}" class="w-16 h-16 object-cover rounded-xl border border-gray-200" alt="Current cover">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Gambar Saat Ini</span>
            </div>
            @endif

            <input type="file" name="gambar" accept="image/*" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white transition text-[13px] font-medium file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[11px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-gray-800 cursor-pointer">
            <p class="text-[10px] text-gray-400 font-medium ml-2">Unggah JPG/PNG baru untuk mengganti sampul saat ini.</p>
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end gap-4 mt-6">
            <button type="submit" class="bg-black text-white px-10 py-3.5 hover:bg-gray-800 font-bold text-[13px] rounded-full transition shadow-md flex items-center gap-3">
                <i class="fas fa-check-circle"></i> <span>Perbarui Database</span>
            </button>
        </div>
    </form>
</div>
@endsection
