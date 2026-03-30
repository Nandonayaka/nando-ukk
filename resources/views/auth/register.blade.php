<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabung - Daftar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FFFFFF; color: #111111; }
    </style>
</head>
<body class="min-h-screen w-full flex items-center justify-center p-6 bg-white">

    <div class="max-w-7xl w-full grid grid-cols-1 md:grid-cols-2 items-center gap-12 lg:gap-24">
        
        <div class="w-full flex justify-center items-center order-2 md:order-1">
            <div class="relative w-full max-w-2xl group">
                <img src="{{ asset('img/image-login.png') }}" 
                     alt="Gabung" 
                     class="w-full h-auto object-contain transform transition-transform duration-700 group-hover:scale-105">
            </div>
        </div>

        <div class="flex flex-col justify-center order-1 md:order-2 px-4 md:px-0">
            <div class="max-w-md w-full mx-auto md:mx-0">
                
                <h1 class="text-5xl md:text-6xl font-extrabold mb-4 tracking-tighter leading-tight text-black">
                    PustakaKu
                </h1>
                
                <p class="text-sm md:text-base text-gray-500 mb-8 leading-relaxed font-medium">
                    Ayo mulai membaca di PustakaKu.
                </p>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-6">
                        <ul class="text-red-600 text-xs font-bold uppercase tracking-wider list-none p-0 m-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-1">
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition-all duration-300 text-sm font-medium placeholder-gray-400" 
                               placeholder="Nama Lengkap" required>
                    </div>

                    <div class="space-y-1">
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition-all duration-300 text-sm font-medium placeholder-gray-400" 
                               placeholder="Alamat Email" required>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <input type="password" name="password" 
                                   class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition-all duration-300 text-sm font-medium placeholder-gray-400" 
                                   placeholder="Kata Sandi" required>
                        </div>
                        <div class="space-y-1">
                            <select name="role" 
                                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-gray-200 focus:border-black focus:bg-white focus:ring-0 outline-none transition-all duration-300 text-sm font-medium text-gray-500 appearance-none cursor-pointer">
                                <option value="pelanggan" selected>Pelanggan</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full md:w-auto px-10 py-4 bg-black text-white hover:bg-gray-800 font-bold text-sm rounded-2xl transition-all duration-300 shadow-lg active:scale-95 flex items-center justify-center">
                            DAFTAR
                        </button>
                    </div>
                </form>

                <div class="mt-10 pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-400 font-medium">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-black hover:underline font-bold ml-1">Masuk disini</a>
                    </p>
                </div>
                
            </div>
        </div>

    </div>

</body>
</html>
