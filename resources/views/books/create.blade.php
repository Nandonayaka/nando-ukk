@extends('books.layout')

@section('content')
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tight mb-2">Tambah Data</h1>
        <p class="text-gray-500 text-sm font-medium">Tambahkan buku baru ke database melalui formulir ini.</p>
    </div>
    <a href="{{ route('books.index') }}" class="bg-white border border-gray-300 text-black hover:border-black px-6 py-3 rounded-full font-semibold text-[13px] transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Daftar
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm max-w-4xl">

    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Judul Buku *</label>
                <input type="text" name="judul" value="{{ old('judul') }}" maxlength="50" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('judul') ? 'border-red-500 text-red-600 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400" placeholder="Masukan Judul Buku">
                @error('judul') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Nama Penulis *</label>
                <input type="text" name="penulis" value="{{ old('penulis') }}" maxlength="50" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('penulis') ? 'border-red-500 text-red-600 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400" placeholder="Masukan Nama Penulis">
                @error('penulis') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Nama Penerbit *</label>
                <input type="text" name="penerbit" value="{{ old('penerbit') }}" maxlength="50" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('penerbit') ? 'border-red-500 text-red-600 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400" placeholder="Masukan Nama Penerbit">
                @error('penerbit') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>
            
            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Tahun Terbit *</label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" min="0" max="9999" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4); this.value = Math.max(0, this.value || 0)" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('tahun_terbit') ? 'border-red-500 text-red-600 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400 appearance-none" placeholder="Masukan Tahun Terbit">
                @error('tahun_terbit') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>

            <div class="space-y-2 relative">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Kategori Buku</label>
                <button type="button" onclick="document.getElementById('cat-dropdown-create').classList.toggle('hidden')" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 text-left hover:border-black outline-none transition text-[13px] font-medium text-gray-700 flex justify-between items-center cursor-pointer shadow-sm">
                    <span>Pilih Kategori (Bisa Lebih Dari 1)</span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>
                
                <div id="cat-dropdown-create" class="hidden absolute z-50 left-0 right-0 mt-2 bg-white border border-gray-100 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] max-h-56 overflow-y-auto p-2">
                    @forelse($kategoris as $kategori)
                        <label class="flex items-center gap-3 px-4 py-2.5 rounded-xl cursor-pointer hover:bg-gray-50 transition group border border-transparent hover:border-gray-100">
                            <input type="checkbox" name="categories[]" value="{{ $kategori->id }}" 
                                {{ (is_array(old('categories')) && in_array($kategori->id, old('categories'))) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black">
                            <span class="text-[13px] font-medium text-gray-600 group-hover:text-black transition">{{ $kategori->nama_kategori }}</span>
                        </label>
                    @empty
                        <div class="p-4 text-center">
                            <span class="text-[11px] text-gray-400 font-medium italic uppercase tracking-widest">Belum ada kategori yang terdaftar.</span>
                        </div>
                    @endforelse
                </div>
                @error('categories') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Stok Awal *</label>
                <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" max="9999" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4); this.value = Math.max(0, this.value || 0)" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('stok') ? 'border-red-500 text-red-600 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400 appearance-none" placeholder="Masukan Jumlah Stok">
                @error('stok') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-2 pt-2">
            <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Deskripsi / Sinopsis</label>
            <textarea name="deskripsi" rows="4" maxlength="255" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('deskripsi') ? 'border-red-500 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400 resize-none" placeholder="Berikan deskripsi detail tentang buku...">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
        </div>

        <div class="space-y-2 pt-2">
            <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Gambar Sampul</label>
            <input type="file" name="gambar" accept="image/*" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border {{ $errors->has('gambar') ? 'border-red-500 bg-red-50/50 focus:border-red-500 text-red-600' : 'border-gray-200 focus:border-black' }} focus:bg-white transition text-[13px] font-medium file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[11px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-gray-800 cursor-pointer">
            <p class="text-[10px] text-gray-400 font-medium ml-2">Unggah gambar JPG, PNG. Biarkan kosong untuk menggunakan ilustrasi default.</p>
            @error('gambar') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end gap-4 mt-6">
            <button type="submit" class="bg-black text-white px-10 py-3.5 hover:bg-gray-800 font-bold text-[13px] rounded-full transition shadow-md flex items-center gap-3">
                <i class="fas fa-check"></i> <span>Simpan Data</span>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('click', function(event) {
        let dropdown = document.getElementById('cat-dropdown-create');
        if (dropdown) {
            let button = dropdown.previousElementSibling;
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        }
    });
</script>
@endsection
