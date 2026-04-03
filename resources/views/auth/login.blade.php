<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - PustakaKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #ffffff; color: #111111; }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row bg-white">

    <!-- Header Section (Hero Image) -->
    <div class="relative w-full md:w-1/2 h-64 md:h-screen bg-gray-50 flex items-center justify-center p-8 overflow-hidden">
        <img src="{{ asset('img/image-login2.png') }}" class="h-full md:w-3/4 object-contain transition-transform duration-700 hover:scale-105">
    </div>

    <!-- Login Form Section -->
    <div class="flex-1 flex items-start md:items-center justify-center p-6 md:p-16 lg:p-24 relative z-10 -mt-12 md:mt-0">
        <div class="bg-white w-full max-w-sm rounded-[2rem] md:rounded-none p-8 md:p-0 shadow-2xl shadow-gray-200/50 md:shadow-none border border-gray-100 md:border-none">
            
            <div class="mb-10">
                <h1 class="text-3xl font-bold tracking-tight text-black mb-1">PustakaKu.</h1>
                <p class="text-gray-400 text-base font-medium">Selamat Datang Di Perpustakaan Digital PustakaKu</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 px-5 py-3 rounded-xl mb-6 border border-red-100 text-sm font-bold uppercase tracking-wider">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-black outline-none transition text-base font-medium" 
                           placeholder="nama@email.com" required>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Kata Sandi</label>
                    <input type="password" name="password" 
                           class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-black outline-none transition text-base font-medium" 
                           placeholder="••••••••" required>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full py-4 bg-black text-white hover:bg-gray-800 font-bold text-sm rounded-xl transition shadow-md uppercase tracking-widest active:scale-95">
                        MASUK
                    </button>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-gray-50 text-center">
                <p class="text-sm text-gray-400 font-bold">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-black hover:underline ml-1">DAFTAR SEKARANG</a>
                </p>
            </div>
            
        </div>
    </div>

</body>
</html>