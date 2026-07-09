@extends('books.layout')

@section('content')
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tight mb-2">Tambah Anggota</h1>
        <p class="text-gray-500 text-sm font-medium">Tambahkan anggota baru melalui formulir ini.</p>
    </div>
    <a href="{{ route('users.index') }}" class="bg-white border border-gray-300 text-black hover:border-black px-6 py-3 rounded-full font-semibold text-[13px] transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Daftar
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm max-w-4xl">
    <form action="{{ route('users.store') }}" method="POST" class="p-8 md:p-12 space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Username *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('name') ? 'border-red-500 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium" placeholder="Username login">
                @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('email') ? 'border-red-500 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium" placeholder="email@anggota.com">
                @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>
            
            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Password *</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required class="w-full px-5 py-3.5 pr-12 rounded-2xl bg-gray-50 border {{ $errors->has('password') ? 'border-red-500 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium" placeholder="Minimal 6 karakter">
                    <button type="button" onclick="togglePassword('password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition p-2">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                @error('password') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Role *</label>
                @php
                    $roleOptions = collect([
                        (object)['id' => 'peminjam', 'nama_kategori' => 'PEMINJAM'],
                        (object)['id' => 'administrator', 'nama_kategori' => 'ADMINISTRATOR']
                    ]);
                @endphp
                @include('partials.filter-dropdown', [
                    'name' => 'role',
                    'options' => $roleOptions,
                    'selected' => old('role', 'peminjam'),
                    'placeholder' => 'PILIH ROLE',
                    'class' => 'no-auto-submit',
                    'align' => 'left-0 sm:right-0'
                ])
                @error('role') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-2 pt-2">
            <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('nama_lengkap') ? 'border-red-500 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium" placeholder="Nama sesuai identitas">
            @error('nama_lengkap') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2 pt-2">
            <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Alamat</label>
            <textarea name="alamat" rows="3" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('alamat') ? 'border-red-500 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium resize-none" placeholder="Alamat lengkap tempat tinggal...">{{ old('alamat') }}</textarea>
            @error('alamat') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</p> @enderror
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end gap-4 mt-6">
            <button type="submit" class="bg-black text-white px-10 py-3.5 hover:bg-gray-800 font-bold text-[13px] rounded-full transition shadow-md flex items-center gap-3">
                <i class="fas fa-check"></i> <span>Simpan Anggota</span>
            </button>
        </div>
    </form>
</div>
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
