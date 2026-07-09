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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #fdfdfd; 
            background-image: radial-gradient(#e2e8f0 0.8px, transparent 0.8px);
            background-size: 24px 24px;
            color: #111111; 
        }
        .bg-dots {
            background-image: radial-gradient(#e2e8f0 0.8px, transparent 0.8px);
            background-size: 24px 24px;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #d1d5db; }

        /* SweetAlert2 Premium Customization */
        .swal2-popup {
            border-radius: 2rem !important;
            padding: 2.5rem !important;
            font-family: 'Poppins', sans-serif !important;
            border: 1px solid #f3f4f6;
        }
        .swal2-title {
            color: #000 !important;
            font-weight: 800 !important;
            letter-spacing: -0.025em !important;
            font-size: 1.5rem !important;
        }
        .swal2-html-container {
            color: #6b7280 !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            margin-top: 1rem !important;
        }
        .swal2-confirm {
            background: #000 !important;
            border-radius: 1rem !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            padding: 1rem 2.5rem !important;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1) !important;
        }
        .swal2-cancel {
            background: transparent !important;
            color: #9ca3af !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
        }
        .swal2-icon {
            border-width: 3px !important;
            scale: 0.8;
            margin-bottom: 2rem !important;
        }

        /* Loading Screen Animation */
        #page-loader {
            position: fixed;
            inset: 0;
            background: #ffffff;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }
        #page-loader.hidden-loader {
            opacity: 0;
            pointer-events: none;
        }
        .loader-logo {
            animation: pulse-zoom 1.5s infinite ease-in-out;
            margin-bottom: 2rem;
        }
        @keyframes pulse-zoom {
            0% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); opacity: 0.7; }
        }
        .loader-bar-container {
            width: 12rem;
            height: 0.375rem;
            background-color: #f3f4f6;
            border-radius: 9999px;
            overflow: hidden;
            position: relative;
        }
        .loader-bar {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 50%;
            background-color: #000;
            border-radius: 9999px;
            animation: slide-bar 1.5s infinite ease-in-out;
        }
        @keyframes slide-bar {
            0% { transform: translateX(-150%); }
            100% { transform: translateX(250%); }
        }

        /* Premium Skeleton System */
        .global-skeleton main h1, .global-skeleton main h2, .global-skeleton main h3, .global-skeleton main p, .global-skeleton main span:not(.text-black),
        .global-skeleton main input, .global-skeleton main button, .global-skeleton main th, .global-skeleton main td {
            background-color: #f3f4f6 !important;
            color: transparent !important;
            border-color: #f3f4f6 !important;
            box-shadow: none !important;
            pointer-events: none !important;
            border-radius: 99px !important;
            animation: soft-pulse 1.5s ease-in-out infinite !important;
        }
        /* Specific squarish elements keep their shape */
        .global-skeleton main input, .global-skeleton main button, .global-skeleton main img, .global-skeleton main th, .global-skeleton main td, .global-skeleton main .rounded-2xl {
            border-radius: 1rem !important;
        }
        /* Hide images and icons neatly */
        .global-skeleton main img {
            background-color: #f3f4f6 !important;
            animation: soft-pulse 1.5s ease-in-out infinite !important;
            content-visibility: hidden;
        }
        .global-skeleton main i {
            opacity: 0 !important;
        }
        @keyframes soft-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Mobile Scrollable Tables */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            padding-bottom: 0.5rem;
        }
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background-color: transparent;
            margin: 0 1rem;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 99px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background-color: #9ca3af;
        }

        /* Global Animated Dropdown System */
        .dropdown-animate {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
        }
        .dropdown-hidden {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
            pointer-events: none;
            display: none;
        }
        .dropdown-visible {
            opacity: 1 !important;
            transform: translateY(0) scale(1) !important;
            pointer-events: auto !important;
            display: block !important;
        }
        .arrow-rotate {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-active .arrow-rotate {
            transform: rotate(180deg);
        }

        /* Skeleton Shimmer Effect for Buttons */
        .btn-skeleton-shimmer {
            position: relative;
            overflow: hidden;
        }
        .btn-skeleton-shimmer::after {
            content: "";
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer-swipe 2s infinite cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        @keyframes shimmer-swipe {
            0% { right: -100%; }
            100% { right: 100%; }
        }

        /* Skeleton Pulse */
        .animate-skeleton {
            background: linear-gradient(90deg, #f3f4f6 25%, #f9fafb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite linear;
        }
        @keyframes skeleton-shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    <style>
        .qr-modal-container {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 200;
            align-items: center;
            justify-content: center;
        }
        .qr-modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.8);
            backdrop-blur: 10px;
        }
        .qr-modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 400px;
            border-radius: 3rem;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            z-index: 10;
        }
        .qr-canvas-wrapper {
            background: #f9fafb;
            padding: 2rem;
            border-radius: 2rem;
            margin: 2rem 0;
            display: inline-block;
            border: 1px solid #f3f4f6;
        }
        #qr-canvas {
            width: 200px !important;
            height: 200px !important;
        }
    </style>
</head>
<body class="global-skeleton flex flex-col lg:flex-row h-screen bg-white overflow-hidden antialiased">
    <!-- Container for Custom Toasts -->
    <div id="toast-container" class="fixed top-6 right-6 z-[200] flex flex-col gap-4"></div>
    <!-- PAGE LOADER -->
    <div id="page-loader">
        <img src="{{ asset('img/logo/logo1.png') }}" class="h-28 w-auto object-contain loader-logo">
        <div class="loader-bar-container">
            <div class="loader-bar"></div>
        </div>
    </div>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-gray-100 flex items-center justify-around px-2 py-3 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
        @auth
            @if(auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas')
            <a href="{{ route('books.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('books.*') ? 'text-black' : 'text-gray-300' }} transition-colors flex-1">
                <i class="fas fa-th-large text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Admin</span>
            </a>
            @endif
            
            <a href="{{ route('katalog.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('katalog.*') ? 'text-black' : 'text-gray-300' }} transition-colors flex-1">
                <i class="fas fa-compass text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Jelajah</span>
            </a>

            <a href="{{ route('history.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('history.*') ? 'text-black' : 'text-gray-300' }} transition-colors flex-1 hidden md:flex">
                <i class="fas fa-history text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">
                    {{ auth()->user()->role === 'peminjam' ? 'Aktivitas' : 'Laporan' }}
                </span>
            </a>

            <a href="{{ route('koleksipribadi.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('koleksipribadi.*') ? 'text-rose-500' : 'text-gray-300' }} transition-colors flex-1">
                <i class="fas fa-heart text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Favorit</span>
            </a>

            <a href="{{ route('gacha.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('gacha.*') ? 'text-black' : 'text-gray-300' }} transition-colors flex-1">
                <i class="fas fa-gift text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Gacha</span>
            </a>

            <a href="{{ route('inbox.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('inbox.*') ? 'text-black' : 'text-gray-300' }} transition-colors flex-1 relative">
                <i class="fas fa-bell text-xl"></i>
                @if(auth()->user()->unreadInboxCount() > 0)
                    <span class="absolute -top-0.5 right-1/4 w-4 h-4 bg-red-500 text-white text-[8px] font-black rounded-full flex items-center justify-center">{{ auth()->user()->unreadInboxCount() > 9 ? '9+' : auth()->user()->unreadInboxCount() }}</span>
                @endif
                <span class="text-[9px] font-bold uppercase tracking-wider">Inbox</span>
            </a>

            <a href="{{ route('profile.index') }}" class="relative group flex-1 flex flex-col items-center gap-1">
                <div class="w-8 h-8 rounded-full overflow-hidden border-2 {{ request()->routeIs('profile.*') ? 'border-black' : 'border-gray-100 shadow-sm' }}">
                    <img src="{{ asset('img/pfp/' . (auth()->user()->pfp ?? 'pfp-m-1.png')) }}" class="w-full h-full object-cover">
                </div>
                 <span class="text-[9px] font-bold {{ request()->routeIs('profile.*') ? 'text-black' : 'text-gray-300' }} uppercase tracking-wider">Profil</span>
            </a>
        @endauth
    </nav>

    <!-- MOBILE SIDEBAR OVERLAY -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden backdrop-blur-sm lg:hidden transition-opacity duration-300"></div>

    <!-- SIDEBAR (Desktop) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-50 flex flex-col transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto transition-transform duration-300 ease-in-out">
        <div class="h-20 flex items-center justify-between px-8 border-b border-gray-100 flex-shrink-0">
            <a href="{{ route('katalog.index') }}" class="flex items-center gap-2 block">
                <img src="{{ asset('img/logo/logo1.png') }}" class="h-20 w-auto object-contain">
            </a>
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-black">
                <i class="fas fa-times text-lg"></i>
            </button>
            <h1 class="text-lg font-bold text-black">PustakaKu.</h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto custom-scrollbar">
            @auth
                {{-- ADMIN SECTION --}}
                @if(auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas')
                <div>
                    <p class="px-4 mb-3 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Manajemen</p>
                    <div class="space-y-1">
                        <a href="{{ route('books.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('books.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                            <i class="fas fa-layer-group {{ request()->routeIs('books.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Buku</span>
                        </a>
                        <a href="{{ route('categories.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('categories.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                            <i class="fas fa-tags {{ request()->routeIs('categories.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Kategori</span>
                        </a>
                        @if(auth()->user()->role === 'administrator')
                        <a href="{{ route('users.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('users.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                            <i class="fas fa-users-cog {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Anggota</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- DISCOVER SECTION --}}
                <div>
                    <p class="px-4 mb-3 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Jelajah</p>
                    <div class="space-y-1">
                        <a href="{{ route('katalog.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('katalog.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                            <i class="fas fa-compass {{ request()->routeIs('katalog.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Katalog Buku</span>
                        </a>
                        <a href="{{ route('gacha.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('gacha.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                            <i class="fas fa-gift {{ request()->routeIs('gacha.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Gacha Hadiah</span>
                        </a>
                    </div>
                </div>

                {{-- PERSONAL SECTION --}}
                <div>
                    <p class="px-4 mb-3 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Koleksi & Aktivitas</p>
                    <div class="space-y-1">
                        <a href="{{ route('koleksipribadi.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('koleksipribadi.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                            <i class="fas fa-heart {{ request()->routeIs('koleksipribadi.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Favorit Saya</span>
                        </a>
                        <a href="{{ route('history.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('history.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                            <i class="fas fa-history {{ request()->routeIs('history.*') ? 'text-white' : 'text-gray-400' }}"></i> 
                            <span>{{ auth()->user()->role === 'peminjam' ? 'Riwayat Pinjam' : 'Laporan Pinjam' }}</span>
                        </a>
                        <a href="{{ route('inbox.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('inbox.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition relative">
                            <i class="fas fa-envelope {{ request()->routeIs('inbox.*') ? 'text-white' : 'text-gray-400' }}"></i> 
                            <span>Pesan Masuk</span>
                            @if(auth()->user()->unreadInboxCount() > 0)
                                <span class="ml-auto bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full min-w-[20px] text-center">{{ auth()->user()->unreadInboxCount() > 9 ? '9+' : auth()->user()->unreadInboxCount() }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                {{-- ACCOUNT SECTION --}}
                <div>
                    <p class="px-4 mb-3 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Pusat Akun</p>
                    <div class="space-y-1">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('profile.*') ? 'bg-black text-white shadow-lg' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                            <i class="fas fa-user-circle {{ request()->routeIs('profile.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Profil & Akun</span>
                        </a>
                    </div>
                </div>
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
        <!-- TOPBAR/navbar pc -->
        <header class="h-20 bg-white/70 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-6 md:px-10 flex-shrink-0 z-30 w-full sticky top-0">
            <div class="flex items-center gap-4">
                <div class="lg:hidden">
                    <a href="{{ route('katalog.index') }}">
                        <img src="{{ asset('img/logo/logo1.png') }}" class="h-6 w-auto object-contain">
                    </a>
                </div>
                <div class="hidden sm:block font-bold text-gray-400 text-[10px] uppercase tracking-widest">
                    {{ request()->routeIs('books.*') ? 'Admin Control' : 'Discover Collections' }}
                </div>
            </div>

            <div class="flex items-center gap-4">
                 @auth
                    <button type="button" onclick="showExpInfo()" class="flex items-center gap-2 bg-gradient-to-r from-yellow-50 to-orange-50 px-4 py-2 rounded-full border border-yellow-200 hover:shadow-md transition-all cursor-pointer focus:outline-none">
                        <i class="fas fa-star text-yellow-500 text-[10px] animate-pulse"></i>
                        <span class="text-[11px] font-black text-yellow-700 tracking-tight global-exp-display">{{ auth()->user()->exp ?? 0 }}</span>
                    </button>

                    <!-- Inbox Bell -->
                  <div class="relative group inline-block">
                        <a href="{{ route('inbox.index') }}"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50">

                            <i class="fas fa-bell text-sm"></i>

                            @if(auth()->user()->unreadInboxCount() > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[9px] rounded-full flex-center">
                                    {{ auth()->user()->unreadInboxCount() }}
                                </span>
                            @endif
                        </a>
                    </div>

                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 bg-gray-50 pr-4 pl-1.5 py-1.5 rounded-full border border-gray-100 hover:bg-white transition-all cursor-pointer">
                        <div class="w-8 h-8 rounded-full overflow-hidden shadow-sm border-2 border-white">
                            <img src="{{ asset('img/pfp/' . (auth()->user()->pfp ?? 'pfp-m-1.png')) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-[10px] font-black text-gray-900 leading-none mb-0.5 tracking-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest leading-none">{{ auth()->user()->role }}</span>
                        </div>
                    </a>
                 @endauth
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-10 max-w-7xl mx-auto w-full">
            @yield('content')

            <!-- GLOBAL FOOTER -->
            <footer class="mt-32 pt-16 border-t border-gray-100 pb-12 overflow-hidden relative">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gray-50 rounded-full blur-3xl opacity-50 -z-10 translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 relative z-10">
                    <div class="col-span-1 md:col-span-2 space-y-6">
                        <a href="{{ route('katalog.index') }}" class="block">
                            <img src="{{ asset('img/logo/logo1.png') }}" class="h-20 w-auto object-contain mb-8">
                        </a>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed max-w-sm opacity-80">
                            Generasi masa depan literasi digital. <br>
                            Memberdayakan pembaca melalui teknologi modern dan ekosistem informasi yang dapat diakses semua orang.
                        </p>
                    </div>
                </div>
            </footer>
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

        // EXP Information Popup
        window.showExpInfo = function() {
            Swal.fire({
                title: 'Cara Mendapatkan EXP',
                icon: 'info',
                confirmButtonColor: '#000',
                confirmButtonText: 'Main Gacha Sekarang',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('gacha.index') }}";
                }
            });
        };

        function hideLoader() {
            const loader = document.getElementById('page-loader');
            if(loader) {
                loader.classList.add('hidden-loader');
                setTimeout(() => { 
                    loader.style.display = 'none'; 
                    document.body.classList.remove('global-skeleton');
                }, 500);
            }
        }

        window.addEventListener('load', function() {
            setTimeout(hideLoader, 400); 
        });

        // Fail-safe
        setTimeout(hideLoader, 10000);

        // Global Custom Dropdown Handler
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(e) {
                const trigger = e.target.closest('.dropdown-trigger');
                const container = e.target.closest('.custom-dropdown');
                
                if (trigger) {
                    const menu = container.querySelector('.dropdown-menu');
                    const isOpen = menu.classList.contains('dropdown-visible');
                    
                    if (isOpen) {
                        menu.classList.remove('dropdown-visible');
                        menu.classList.add('dropdown-hidden');
                        trigger.classList.remove('btn-active');
                    } else {
                        menu.classList.remove('dropdown-hidden');
                        menu.classList.add('dropdown-visible');
                        trigger.classList.add('btn-active');
                    }
                }

                const item = e.target.closest('.dropdown-item');
                if (item) {
                     const val = item.dataset.val;
                     const label = item.dataset.label;
                     const input = container.querySelector('input[type="hidden"]');
                     const labelSpan = container.querySelector('.dropdown-label');
                     
                     if(input) input.value = val;
                     if(labelSpan) labelSpan.innerText = label;
                     
                     if(input && input.form && !container.classList.contains('no-auto-submit')) {
                        input.form.submit();
                     }
                }
            });
        });
    </script>
</body>
</html>
