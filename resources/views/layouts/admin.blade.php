<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - EquipRent Control Panel</title>

    <!-- Tailwind & App assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex antialiased" x-data="{ sidebarOpen: false }">

    <!-- 1. Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         class="fixed inset-0 z-40 bg-slate-950/80 backdrop-blur-sm lg:hidden" 
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <!-- 2. Sidebar Navigation (Responsive) -->
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 border-r border-slate-800 glass transform lg:translate-x-0 transition-transform duration-300 flex flex-col justify-between"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        
        <div class="p-6 space-y-8">
            <!-- Brand Logo -->
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                    <span class="p-2 rounded-lg bg-gradient-to-tr from-emerald-600 to-emerald-400 text-slate-950 shadow-md">
                        <i data-lucide="tent" class="h-5 w-5"></i>
                    </span>
                    <span class="font-display font-extrabold text-lg tracking-tight text-white">
                        Equip<span class="text-emerald-500 font-bold">Rent</span> <span class="text-[10px] text-slate-500 font-bold ml-1">ADMIN</span>
                    </span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <!-- Nav Links List -->
            <nav class="flex flex-col gap-1 text-xs">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium transition-all {{ Route::is('admin.dashboard') ? 'bg-emerald-500 text-slate-950 font-bold shadow-md shadow-emerald-500/10' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="h-4.5 w-4.5"></i> Dashboard Utama
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium transition-all {{ Route::is('admin.categories.*') ? 'bg-emerald-500 text-slate-950 font-bold shadow-md shadow-emerald-500/10' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white' }}">
                    <i data-lucide="folder" class="h-4.5 w-4.5"></i> Kelola Kategori
                </a>

                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium transition-all {{ Route::is('admin.products.*') ? 'bg-emerald-500 text-slate-950 font-bold shadow-md shadow-emerald-500/10' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white' }}">
                    <i data-lucide="package" class="h-4.5 w-4.5"></i> Kelola Perlengkapan
                </a>

                <a href="{{ route('admin.rentals.index') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium transition-all {{ Route::is('admin.rentals.*') ? 'bg-emerald-500 text-slate-950 font-bold shadow-md shadow-emerald-500/10' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white' }}">
                    <i data-lucide="shopping-bag" class="h-4.5 w-4.5"></i> Transaksi Sewa
                </a>

                <a href="{{ route('admin.reports.index') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium transition-all {{ Route::is('admin.reports.*') ? 'bg-emerald-500 text-slate-950 font-bold shadow-md shadow-emerald-500/10' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white' }}">
                    <i data-lucide="bar-chart-3" class="h-4.5 w-4.5"></i> Laporan Keuangan
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium transition-all {{ Route::is('admin.users.*') ? 'bg-emerald-500 text-slate-950 font-bold shadow-md shadow-emerald-500/10' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white' }}">
                    <i data-lucide="users" class="h-4.5 w-4.5"></i> Kelola User
                </a>
            </nav>
        </div>

        <!-- Sidebar footer -->
        <div class="p-6 border-t border-slate-800 space-y-4">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs uppercase">
                    AD
                </div>
                <div>
                    <h5 class="text-xs font-bold text-white leading-none">{{ auth()->user()->name }}</h5>
                    <p class="text-[10px] text-slate-500 mt-1 uppercase font-semibold">ROLE: ADMIN</p>
                </div>
            </div>
            
            <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-950 hover:bg-slate-800 text-[11px] text-emerald-400 font-bold transition-all border border-slate-800 hover:border-emerald-500/30">
                <i data-lucide="arrow-left-right" class="h-3.5 w-3.5"></i> Kembali Ke Toko
            </a>
        </div>

    </aside>

    <!-- 3. Page Content Area -->
    <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
        
        <!-- Top Toolbar Header -->
        <header class="h-16 border-b border-slate-900 bg-slate-950 flex items-center justify-between px-6 sticky top-0 z-30">
            <!-- Left togglers -->
            <button @click="sidebarOpen = true" class="lg:hidden text-slate-400 hover:text-white focus:outline-none">
                <i data-lucide="menu" class="h-6 w-6"></i>
            </button>
            <div class="hidden lg:flex items-center gap-1.5 text-xs text-slate-500">
                <span>Control Panel</span>
                <i data-lucide="chevron-right" class="h-3.0 w-3.0"></i>
                <span class="text-slate-300 font-bold">@yield('page_title', 'Dashboard')</span>
            </div>

            <!-- Right actions -->
            <div class="flex items-center gap-4 text-xs">
                <button type="button" data-theme-toggle class="flex items-center gap-2 rounded-lg border border-slate-800 bg-slate-900/80 px-3 py-1.5 font-semibold text-slate-200 transition-colors hover:border-emerald-500/40 hover:text-white">
                    <i data-lucide="sun" data-theme-icon class="h-4 w-4"></i>
                    <span data-theme-label>Mode gelap</span>
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-800 hover:border-red-900/30 hover:bg-red-950/40 text-slate-400 hover:text-red-400 transition-all font-bold">
                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i> Keluar
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-grow p-6 sm:p-8 bg-slate-950">
            <!-- Toast notification mapping inside admin -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-950/20 border border-emerald-900/30 text-emerald-400 text-xs flex items-center gap-2">
                    <i data-lucide="check-circle" class="h-4.5 w-4.5"></i> {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-950/20 border border-red-900/30 text-red-400 text-xs flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="h-4.5 w-4.5"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    <!-- Initialize Lucide icons inside admin -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
