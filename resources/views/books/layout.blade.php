<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FFFFFF; color: #111111; }
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
<body class="flex h-screen overflow-hidden antialiased">
    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 h-screen flex flex-col flex-shrink-0 z-20">
        <div class="h-20 flex items-center px-8 border-b border-gray-100">
            <h1 class="font-extrabold text-2xl tracking-tight text-black flex items-center gap-2">
                <i class="fas fa-book"></i> Perpus.
            </h1>
        </div>
        <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('books.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('books.*') ? 'bg-black text-white' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                        <i class="fas fa-layer-group {{ request()->routeIs('books.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Library</span>
                    </a>
                @endif
                <a href="{{ route('katalog.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('katalog.*') ? 'bg-black text-white' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                    <i class="fas fa-compass {{ request()->routeIs('katalog.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>Explore</span>
                </a>
                
                @if(auth()->user()->role === 'pelanggan')
                    <a href="{{ route('history.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('history.*') ? 'bg-black text-white' : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-black' }} rounded-2xl font-semibold text-[13px] transition">
                        <i class="fas fa-history {{ request()->routeIs('history.*') ? 'text-white' : 'text-gray-400' }}"></i> <span>History</span>
                    </a>
                @endif
            @endauth
        </nav>
        <div class="p-6 border-t border-gray-100 mt-auto">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center">Version 1.0.0</p>
        </div>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative bg-dots">
        <!-- TOPBAR -->
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-10 flex-shrink-0 z-10 w-full">
            <div class="font-bold text-gray-400 text-sm uppercase tracking-widest">Dashboard Area</div>
            <div class="flex items-center gap-4">
                <div class="relative group cursor-pointer inline-block">
                    <div class="flex items-center gap-3 bg-white border border-gray-200 px-4 py-2 rounded-full hover:border-black transition">
                        <div class="text-right">
                            <p class="font-bold text-[13px] text-black leading-none">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">{{ auth()->user()->role ?? 'Guest' }}</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600"><i class="fas fa-user text-[11px]"></i></div>
                    </div>
                    <!-- Dropdown Content -->
                    <div class="hidden group-hover:block absolute right-0 top-full pt-2 w-48 z-50">
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden py-2">
                             <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="w-full text-left px-5 py-2.5 text-[13px] font-semibold text-red-500 hover:bg-gray-50 transition flex items-center gap-3">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 overflow-y-auto p-10 max-w-6xl mx-auto w-full">
            @if ($message = Session::get('success'))
                <div class="bg-white border text-[13px] border-gray-200 px-6 py-4 mb-8 text-black font-semibold rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-gray-400"></i> {{ $message }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
