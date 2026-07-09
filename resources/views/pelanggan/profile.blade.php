@extends('books.layout')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <!-- Header Section -->
    <div class="mb-10 text-center md:text-left px-1">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Profil Saya</h1>
        <p class="text-gray-400 text-sm font-medium">Kelola informasi akun dan preferensi Anda.</p>
    </div>

    <form id="profile-form" action="{{ route('profile.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-10 px-1">
        @csrf
        @method('PUT')
        
        <!-- Left Column -->
        <div class="space-y-10">
            <!-- Identity Card -->
            <div class="bg-white border-2 border-gray-50 rounded-[2.5rem] p-10 shadow-2xl shadow-black/[0.03] text-center">
                <div class="relative inline-block mb-8 group">
                    <div class="w-44 h-44 rounded-full overflow-hidden border-8 border-gray-50 shadow-inner group-hover:border-black transition-all duration-700 transform group-hover:scale-105">
                        <img src="{{ asset('img/pfp/' . (auth()->user()->pfp ?? 'pfp-m-1.png')) }}" class="w-full h-full object-cover">
                    </div>
                    <a href="{{ route('pfp.choose') }}" class="absolute bottom-2 right-2 bg-black text-white w-12 h-12 rounded-full flex items-center justify-center border-4 border-white hover:scale-125 transition-all shadow-xl">
                        <i class="fas fa-camera text-sm"></i>
                    </a>
                </div>
                
                <h2 class="text-3xl font-black text-gray-900 mb-2 tracking-tight">{{ auth()->user()->name }}</h2>
                <div class="inline-block px-5 py-1.5 bg-gray-50 border border-gray-100 rounded-full mb-10">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">{{ auth()->user()->role }}</span>
                </div>

                <div class="space-y-6 text-left">
                    <div>
                        <label class="block text-[10px] font-black text-gray-300 uppercase tracking-widest mb-3 ml-2">Email Akun</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" 
                            class="w-full bg-gray-50 border-2 border-transparent focus:border-black focus:bg-white rounded-[1.5rem] px-7 py-5 text-sm font-bold text-gray-700 outline-none transition-all shadow-sm"
                            placeholder="email@contoh.com">
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 border-2 border-red-100 rounded-[2rem] p-8">
                <button type="button" onclick="document.getElementById('logout-form').submit();" class="w-full bg-red-500 text-white px-8 py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-600 transition-all active:scale-95 shadow-xl shadow-red-500/20 flex items-center justify-center gap-4">
                    <i class="fas fa-power-off"></i> Keluar Aplikasi
                </button>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-10">
            <!-- Edit Details Card -->
            <div class="bg-white border-2 border-gray-50 rounded-[2.5rem] p-10 shadow-2xl shadow-black/[0.03]">
                <div class="flex items-center justify-between mb-12">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-black text-white flex items-center justify-center shadow-2xl shadow-black/20">
                            <i class="fas fa-user text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-xl text-gray-900 tracking-tight leading-none mb-1">Data Personal</h3>
                            <p class="text-gray-400 text-[10px] uppercase font-bold tracking-widest leading-none">Sinkronisasi Identitas</p>
                        </div>
                    </div>
                    <button type="button" id="btn-save-profile" class="bg-black text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-black/10">
                        Simpan
                    </button>
                </div>

                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-300 uppercase tracking-widest mb-3 ml-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ auth()->user()->nama_lengkap ?? auth()->user()->name }}" 
                            class="w-full bg-gray-50 border-2 border-transparent focus:border-black focus:bg-white rounded-[1.5rem] px-7 py-5 text-sm font-bold text-gray-700 outline-none transition-all shadow-sm">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-300 uppercase tracking-widest mb-3 ml-2">Username</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-black focus:bg-white rounded-[1.5rem] px-7 py-5 text-sm font-bold text-gray-700 outline-none transition-all shadow-sm">
                        </div>
                        <div class="relative">
                            <label class="block text-[10px] font-black text-gray-300 uppercase tracking-widest mb-3 ml-2">Sandi Baru</label>
                            <input type="password" name="password" id="password-input"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-black focus:bg-white rounded-[1.5rem] px-7 py-5 pr-14 text-sm font-bold text-gray-700 outline-none transition-all shadow-sm" placeholder="Abaikan bila aman">
                            <button type="button" onclick="const p = document.getElementById('password-input'); const i = document.getElementById('eye-icon'); if(p.type === 'password') { p.type = 'text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash'); } else { p.type = 'password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye'); }" class="absolute bottom-5 right-5 text-gray-400 hover:text-black transition">
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-300 uppercase tracking-widest mb-3 ml-2">Alamat Lengkap</label>
                        <textarea name="alamat" 
                            class="w-full bg-gray-50 border-2 border-transparent focus:border-black focus:bg-white rounded-[1.5rem] px-7 py-5 text-sm font-bold text-gray-700 min-h-[160px] leading-relaxed outline-none transition-all shadow-sm">{{ auth()->user()->alamat ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnSave = document.getElementById('btn-save-profile');
    if(btnSave) {
        btnSave.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Apakah Anda yakin ingin memperbarui data profil ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('profile-form').submit();
                }
            })
        });
    }
});
</script>
@endsection
