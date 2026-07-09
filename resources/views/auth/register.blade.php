<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - PustakaKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #fdfdfd; 
            background-image: radial-gradient(#e2e8f0 0.8px, transparent 0.8px);
            background-size: 24px 24px;
            color: #111111; 
        }
        @view-transition { navigation: auto; }
        .hero-section { view-transition-name: hero-section; }
        .form-section { view-transition-name: form-section; }
        ::view-transition-group(hero-section),
        ::view-transition-group(form-section) {
            animation-duration: 0.9s;
            animation-timing-function: cubic-bezier(0.22, 1, 0.36, 1);
        }
        .slide-in-right { animation: slideRight 1s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
        .slide-in-left { animation: slideLeft 1s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
        @keyframes slideRight { 0% { transform: translateX(80px); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
        @keyframes slideLeft { 0% { transform: translateX(-80px); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }

        /* Premium Skeleton System */
        .global-skeleton h1, .global-skeleton p, .global-skeleton label, .global-skeleton a.text-black {
            background-color: #f3f4f6 !important;
            color: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
            pointer-events: none !important;
            border-radius: 99px !important;
            animation: soft-pulse 1.5s ease-in-out infinite !important;
            display: inline-block;
        }
        .global-skeleton input, .global-skeleton textarea, .global-skeleton button, .global-skeleton img {
            background-color: #f3f4f6 !important;
            color: transparent !important;
            border-color: transparent !important;
            pointer-events: none !important;
            border-radius: 1rem !important;
            animation: soft-pulse 1.5s ease-in-out infinite !important;
        }
        .global-skeleton img { content-visibility: hidden; }
        @keyframes soft-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body class="global-skeleton min-h-screen flex flex-col md:flex-row-reverse bg-white">
    <div id="toast-container" class="fixed top-6 right-6 z-[200] flex flex-col gap-4"></div>

    <!-- Header Section (Hero Image) -->
    <div class="hero-section relative w-full md:w-1/2 h-64 md:h-screen bg-gray-50 flex items-center justify-center p-8 overflow-hidden">
        <img src="{{ asset('img/image-login.png') }}" class="h-full md:w-3/4 object-contain transition-transform duration-700 hover:scale-105 slide-in-right">
    </div>

    <!-- Register Form Section -->
    <div class="form-section flex-1 flex items-start md:items-center justify-center p-6 md:p-16 lg:p-24 relative z-10 -mt-12 md:mt-0">
        <div class="bg-white w-full max-w-md rounded-[2rem] md:rounded-none p-8 md:p-0 shadow-2xl shadow-gray-200/50 md:shadow-none border border-gray-100 md:border-none slide-in-left">
            
            <div class="mb-10 text-left">
                <h1 class="text-3xl font-bold tracking-tight text-black mb-1">Daftar Akun</h1>
                <p class="text-gray-400 text-base font-medium">Mulai literasi digital Anda di Pustaka.</p>
            </div>



            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-5 py-3.5 rounded-xl border {{ $errors->has('name') ? 'border-red-500 text-red-600 focus:border-red-500 bg-red-50/50' : 'border-gray-200 focus:border-black' }} outline-none transition text-base font-medium" 
                           placeholder="Nama Anda" required>
                    @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-5 py-3.5 rounded-xl border {{ $errors->has('email') ? 'border-red-500 text-red-600 focus:border-red-500 bg-red-50/50' : 'border-gray-200 focus:border-black' }} outline-none transition text-base font-medium" 
                           placeholder="email@pustakaku.com" required>
                    @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               class="w-full px-5 py-3.5 rounded-xl border {{ $errors->has('password') ? 'border-red-500 text-red-600 focus:border-red-500 bg-red-50/50' : 'border-gray-200 focus:border-black' }} outline-none transition text-base font-medium pr-12" 
                               placeholder="••••••••" required>
                        <button type="button" onclick="togglePassword('password', 'eye-icon')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition-colors">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                </div>

                       <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-widest text-gray-500">Umur*</label>
                <input type="number" name="stok"  min="0" max="9999" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4); this.value = Math.max(0, this.value || 0)" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('stok') ? 'border-red-500 text-red-600 bg-red-50/50 focus:border-red-500' : 'border-gray-200 focus:border-black' }} focus:bg-white focus:ring-0 outline-none transition text-[13px] font-medium placeholder-gray-400 appearance-none">
            </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Alamat Lengkap (Opsional)</label>
                    <textarea name="alamat" rows="3"
                           class="w-full px-5 py-3.5 rounded-xl border {{ $errors->has('alamat') ? 'border-red-500 text-red-600 focus:border-red-500 bg-red-50/50' : 'border-gray-200 focus:border-black' }} outline-none transition text-base font-medium resize-none" 
                           placeholder="Contoh: Jl. Merdeka No. 123">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full py-4 bg-black text-white hover:bg-gray-800 font-bold text-sm rounded-xl transition shadow-md uppercase tracking-widest active:scale-95">
                        DAFTAR
                    </button>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-gray-50 text-center">
                <p class="text-sm text-gray-400 font-bold">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-black hover:underline ml-1 uppercase">Masuk Di Sini</a>
                </p>
            </div>
            
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.body.classList.remove('global-skeleton');
            }, 600); // transisi skeleton pudar
        });

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
            }
        }
    </script>
    <script>
        function showCustomToast(type, title, message) {
            const container = document.getElementById('toast-container');
            if(!container) return;
            
            const toast = document.createElement('div');
            let colorConfig = '';
            let iconSvg = '';
            
            if(type === 'success') {
                colorConfig = 'bg-[#edfcf2] border-[#bbf0ce] text-[#168a41] shadow-[0_15px_40px_-10px_rgba(22,138,65,0.15)]';
                iconSvg = `<svg class="w-6 h-6 text-[#168a41]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            } else if(type === 'error') {
                colorConfig = 'bg-[#fef2f2] border-[#fecaca] text-[#dc2626] shadow-[0_15px_40px_-10px_rgba(220,38,38,0.15)]';
                iconSvg = `<svg class="w-6 h-6 text-[#dc2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            }
            
            toast.className = `flex items-center gap-3.5 py-3 px-5 rounded-[2rem] border ${colorConfig} transform transition-all duration-400 translate-x-12 opacity-0 min-w-[280px] cursor-pointer backdrop-blur-md`;
            
            toast.innerHTML = `
                <div class="flex-shrink-0">${iconSvg}</div>
                <div class="flex-1 flex flex-col justify-center">
                    <h4 class="text-[13px] font-bold leading-tight">${title}</h4>
                    <p class="text-[11px] font-medium opacity-80 leading-tight mt-0.5">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 opacity-40 hover:opacity-100 transition-opacity p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-12', 'opacity-0');
            }, 50);
            
            setTimeout(() => {
                if(toast.parentElement) {
                    toast.classList.add('opacity-0', 'translate-x-12');
                    setTimeout(() => toast.remove(), 400);
                }
            }, 4000);
        }

        @if(Session::has('success'))
            showCustomToast('success', 'Berhasil Daftar', "{{ Session::get('success') }}");
        @endif
        @if(Session::has('error'))
            showCustomToast('error', 'Gagal', "{{ Session::get('error') }}");
        @endif
        @if($errors->any())
            showCustomToast('error', 'Pendaftaran Ditolak', "{{ $errors->first() }}");
        @endif
    </script>
</body>
</html>
