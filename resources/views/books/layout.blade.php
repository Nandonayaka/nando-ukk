<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #FFFFFF; color: #111111; }
        .bg-dots {
            background-color: #FAFAFA;
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 32px 32px;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</head>
<body class="flex flex-col lg:flex-row h-screen bg-white overflow-hidden antialiased">
    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-gray-100 flex items-center justify-around px-2 py-3 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
        @auth
            @if(auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas')
            <a href="{{ route('books.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('books.*') ? 'text-black' : 'text-gray-300' }} transition-colors w-1/4">
                <i class="fas fa-th-large text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Admin</span>
            </a>
            @endif
            
            <a href="{{ route('katalog.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('katalog.*') ? 'text-black' : 'text-gray-300' }} transition-colors w-1/4">
                <i class="fas fa-compass text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Jelajah</span>
            </a>

            <a href="{{ route('history.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('history.*') ? 'text-black' : 'text-gray-300' }} transition-colors w-1/4">
                <i class="fas fa-history text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">
                    {{ auth()->user()->role === 'peminjam' ? 'Koleksi' : 'Laporan' }}
                </span>
            </a>

            <div class="relative group w-1/4 flex flex-col items-center gap-1 text-gray-300">
                <i class="fas fa-user-circle text-xl"></i>
                 <span class="text-[9px] font-bold uppercase tracking-wider">Profil</span>
            </div>
        @endauth
    </nav>

    <!-- MOBILE SIDEBAR OVERLAY -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden backdrop-blur-sm lg:hidden transition-opacity duration-300"></div>

    <!-- SIDEBAR (Desktop) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-50 flex flex-col transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto transition-transform duration-300 ease-in-out">
        <div class="h-20 flex items-center justify-between px-8 border-b border-gray-100 flex-shrink-0">
            <h1 class="font-extrabold text-2xl tracking-tight text-black flex items-center gap-2">
                <i class="fas fa-book"></i> Pustaka.
            </h1>
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-black">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
            @auth
                @if(auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas')
                    <a href="{{ route('books.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('books.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                        <i class="fas fa-layer-group {{ request()->routeIs('books.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Manajemen Buku</span>
                    </a>
                    @if(auth()->user()->role === 'administrator')
                    <a href="{{ route('users.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('users.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                        <i class="fas fa-users-cog {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Kelola Anggota</span>
                    </a>
                    @endif
                @endif
                <a href="{{ route('katalog.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('katalog.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                    <i class="fas fa-compass {{ request()->routeIs('katalog.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Jelajah Buku</span>
                </a>
                
                <a href="{{ route('history.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('history.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                    <i class="fas fa-history {{ request()->routeIs('history.*') ? 'text-white' : 'text-gray-400' }}"></i> 
                    <span>{{ auth()->user()->role === 'peminjam' ? 'Koleksi Saya' : 'Data Peminjaman' }}</span>
                </a>
            @endauth
        </nav>
        <div class="p-6 border-t border-gray-100 flex-shrink-0">
            <div class="bg-gray-50 p-4 rounded-2xl text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center border border-gray-100">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700 transition">LOGOUT AKUN</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative bg-dots pb-16 lg:pb-0">
        <!-- TOPBAR -->
        <header class="h-20 bg-white/70 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-6 md:px-10 flex-shrink-0 z-30 w-full sticky top-0">
            <div class="flex items-center gap-4">
                <div class="lg:hidden">
                    <h1 class="font-extrabold text-xl tracking-tight text-black flex items-center gap-2">
                        <i class="fas fa-book"></i>
                    </h1>
                </div>
                <div class="hidden sm:block font-bold text-gray-400 text-[10px] uppercase tracking-widest">
                    {{ request()->routeIs('books.*') ? 'Admin Control' : 'Discover Collections' }}
                </div>
            </div>

            <div class="flex items-center gap-4">
                 @auth
                    <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-full border border-gray-100">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ auth()->user()->role }}</span>
                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                    </div>
                 @endauth
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-10 max-w-7xl mx-auto w-full">
            @if ($message = Session::get('success'))
                <div class="bg-white border border-gray-200 px-6 py-4 mb-8 text-black font-semibold rounded-2xl flex items-center gap-3 shadow-md border-l-4 border-l-black animate-pulse">
                    <i class="fas fa-check-circle text-black"></i> {{ $message }}
                </div>
            @endif
            @if($errors->any())
                 <div class="bg-red-50 border border-red-100 px-6 py-4 mb-8 text-red-600 font-bold rounded-2xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isHidden = sidebar.classList.contains('-translate-x-full');
            
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
