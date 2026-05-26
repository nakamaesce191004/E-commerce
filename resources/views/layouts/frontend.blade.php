<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'EquipRent') - Premium Camera & Outdoor Camping Gear Rental</title>
    <meta name="description" content="@yield('meta_description', 'Sewa Kamera, Lensa, Drone, Tenda Camping & Perlengkapan Outdoor Premium Terbaik dengan Mudah, Cepat & Terpercaya.')">

    <!-- Tailwind & App assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-slate-950">

    <!-- Toast Notification System (Alpine.js) -->
    <div x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            init() {
                @if(session('success'))
                    this.trigger('{{ session('success') }}', 'success');
                @elseif(session('error'))
                    this.trigger('{{ session('error') }}', 'error');
                @endif
            },
            trigger(msg, type) {
                this.message = msg;
                this.type = type;
                this.show = true;
                setTimeout(() => { this.show = false; }, 4000);
            }
         }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 max-w-sm w-full bg-slate-900 border-l-4 shadow-2xl rounded-lg overflow-hidden glass p-4"
         :class="type === 'success' ? 'border-emerald-500' : 'border-orange-500'"
         style="display: none;"
         @toast.window="trigger($event.detail.message, $event.detail.type || 'success')">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <template x-if="type === 'success'">
                    <i data-lucide="check-circle" class="h-6 w-6 text-emerald-400"></i>
                </template>
                <template x-if="type === 'error'">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-orange-400"></i>
                </template>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-semibold text-white" x-text="type === 'success' ? 'Sukses!' : 'Oops, Terjadi Kesalahan!'"></p>
                <p class="mt-1 text-sm text-slate-300" x-text="message"></p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="bg-transparent rounded-md inline-flex text-slate-400 hover:text-slate-200 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sticky Navigation Bar -->
    <header x-data="{ mobileMenuOpen: false, scrolled: false }"
            @scroll.window="scrolled = (window.pageYOffset > 20) ? true : false"
            class="sticky top-0 z-40 transition-all duration-300 w-full"
            :class="scrolled ? 'bg-slate-950/80 backdrop-blur-md border-b border-slate-900 py-3 shadow-lg' : 'bg-transparent py-5'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="p-2 rounded-lg bg-gradient-to-tr from-emerald-600 to-emerald-400 text-slate-950 shadow-md group-hover:scale-105 transition-all">
                        <i data-lucide="tent" class="h-5 w-5"></i>
                    </span>
                    <span class="font-display font-extrabold text-2xl tracking-tight text-white group-hover:text-emerald-400 transition-colors">
                        Equip<span class="text-emerald-500">Rent</span>
                    </span>
                </a>

                <!-- Desktop Nav Items -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium transition-colors hover:text-emerald-400 {{ Route::is('home') ? 'text-emerald-400 font-semibold' : 'text-slate-300' }}">Home</a>
                    <a href="{{ route('catalog.index') }}" class="text-sm font-medium transition-colors hover:text-emerald-400 {{ Route::is('catalog.*') ? 'text-emerald-400 font-semibold' : 'text-slate-300' }}">Katalog</a>
                    <a href="{{ route('about') }}" class="text-sm font-medium transition-colors hover:text-emerald-400 {{ Route::is('about') ? 'text-emerald-400 font-semibold' : 'text-slate-300' }}">Tentang Kami</a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium transition-colors hover:text-emerald-400 {{ Route::is('contact') ? 'text-emerald-400 font-semibold' : 'text-slate-300' }}">Hubungi Kami</a>
                </nav>

                <!-- Action Utilities (Cart, Wishlist, User Profile) -->
                <div class="hidden md:flex items-center gap-5">
                    
                    <!-- Wishlist Link -->
                    <a href="{{ route('wishlist.index') }}" class="relative text-slate-300 hover:text-emerald-400 transition-colors p-1.5 hover:bg-slate-900 rounded-lg" title="Wishlist">
                        <i data-lucide="heart" class="h-5 w-5"></i>
                        @auth
                            @if(auth()->user()->wishlists()->count() > 0)
                                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-orange-600 text-[10px] font-bold text-white ring-2 ring-slate-950">
                                    {{ auth()->user()->wishlists()->count() }}
                                </span>
                            @endif
                        @endauth
                    </a>

                    <!-- Cart Link -->
                    <a href="{{ route('cart.index') }}" class="relative text-slate-300 hover:text-emerald-400 transition-colors p-1.5 hover:bg-slate-900 rounded-lg" title="Keranjang Rental">
                        <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500 text-[10px] font-bold text-slate-950 ring-2 ring-slate-950 animate-pulse">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <!-- Auth Dropdown -->
                    @auth
                        <div x-data="{ open: false }" class="relative" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none p-1 rounded-lg hover:bg-slate-900 transition-colors">
                                <div class="h-8 w-8 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <span class="text-sm font-medium text-slate-200 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 origin-top-right rounded-lg bg-slate-900 border border-slate-800 shadow-xl py-1 glass"
                                 style="display: none;">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-800 hover:text-emerald-400 transition-colors">
                                        <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Admin Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-800 hover:text-emerald-400 transition-colors">
                                        <i data-lucide="user" class="h-4 w-4"></i> Dashboard Saya
                                    </a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-800 hover:text-emerald-400 transition-colors">
                                    <i data-lucide="settings" class="h-4 w-4"></i> Pengaturan
                                </a>
                                <hr class="border-slate-800 my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-sm text-red-400 hover:bg-slate-800 transition-colors">
                                        <i data-lucide="log-out" class="h-4 w-4"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-sm font-semibold bg-emerald-500 text-slate-950 hover:bg-emerald-400 transition-all shadow-md hover:shadow-emerald-500/20">Daftar</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden gap-4">
                    <a href="{{ route('cart.index') }}" class="relative text-slate-300 p-1.5">
                        <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500 text-[10px] font-bold text-slate-950">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-300 hover:text-white focus:outline-none p-1">
                        <template x-if="!mobileMenuOpen">
                            <i data-lucide="menu" class="h-6 w-6"></i>
                        </template>
                        <template x-if="mobileMenuOpen">
                            <i data-lucide="x" class="h-6 w-6"></i>
                        </template>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-slate-950 border-b border-slate-900 py-4 px-4 shadow-xl glass"
             style="display: none;">
            <div class="flex flex-col gap-4">
                <a href="{{ route('home') }}" class="text-base font-medium text-slate-300 hover:text-emerald-400 py-1">Home</a>
                <a href="{{ route('catalog.index') }}" class="text-base font-medium text-slate-300 hover:text-emerald-400 py-1">Katalog</a>
                <a href="{{ route('about') }}" class="text-base font-medium text-slate-300 hover:text-emerald-400 py-1">Tentang Kami</a>
                <a href="{{ route('contact') }}" class="text-base font-medium text-slate-300 hover:text-emerald-400 py-1">Hubungi Kami</a>
                <a href="{{ route('wishlist.index') }}" class="text-base font-medium text-slate-300 hover:text-emerald-400 py-1 flex items-center gap-2">
                    <i data-lucide="heart" class="h-5 w-5"></i> Wishlist Saya
                </a>
                <hr class="border-slate-900 my-1">
                @auth
                    <div class="flex items-center gap-3 py-2">
                        <div class="h-9 w-9 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-300 hover:text-emerald-400 py-1 flex items-center gap-2">
                            <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-300 hover:text-emerald-400 py-1 flex items-center gap-2">
                            <i data-lucide="user" class="h-4 w-4"></i> Dashboard Saya
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-slate-300 hover:text-emerald-400 py-1 flex items-center gap-2">
                        <i data-lucide="settings" class="h-4 w-4"></i> Pengaturan
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 px-3 rounded-lg text-sm font-semibold bg-red-950/40 text-red-400 hover:bg-red-900/40 transition-colors flex items-center gap-2">
                            <i data-lucide="log-out" class="h-4 w-4"></i> Keluar
                        </button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-lg text-sm font-semibold border border-slate-800 text-slate-300 hover:bg-slate-900 hover:text-white">Masuk</a>
                        <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-lg text-sm font-semibold bg-emerald-500 text-slate-950 hover:bg-emerald-400">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 mt-20 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
                
                <!-- Col 1: Brand -->
                <div class="lg:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-5">
                        <span class="p-2 rounded-lg bg-emerald-500 text-slate-950 shadow-md">
                            <i data-lucide="tent" class="h-5 w-5"></i>
                        </span>
                        <span class="font-display font-extrabold text-2xl tracking-tight text-white">
                            Equip<span class="text-emerald-500">Rent</span>
                        </span>
                    </a>
                    <p class="text-sm text-slate-400 max-w-sm mb-6 leading-relaxed">
                        Penyedia jasa persewaan kamera profesional dan peralatan outdoor camping terlengkap di Indonesia. Berkualitas tinggi, steril, aman, dan siap menemani setiap petualangan seru Anda!
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="h-9 w-9 rounded-lg bg-slate-900 text-slate-400 hover:text-emerald-400 hover:bg-slate-800 transition-all flex items-center justify-center">
                            <i data-lucide="facebook" class="h-5 w-5"></i>
                        </a>
                        <a href="#" class="h-9 w-9 rounded-lg bg-slate-900 text-slate-400 hover:text-emerald-400 hover:bg-slate-800 transition-all flex items-center justify-center">
                            <i data-lucide="instagram" class="h-5 w-5"></i>
                        </a>
                        <a href="#" class="h-9 w-9 rounded-lg bg-slate-900 text-slate-400 hover:text-emerald-400 hover:bg-slate-800 transition-all flex items-center justify-center">
                            <i data-lucide="twitter" class="h-5 w-5"></i>
                        </a>
                        <a href="#" class="h-9 w-9 rounded-lg bg-slate-900 text-slate-400 hover:text-emerald-400 hover:bg-slate-800 transition-all flex items-center justify-center">
                            <i data-lucide="youtube" class="h-5 w-5"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Kategori Kamera -->
                <div>
                    <h4 class="font-display font-bold text-white text-sm uppercase tracking-wider mb-5">Peralatan Kamera</h4>
                    <ul class="flex flex-col gap-3 text-sm">
                        <li><a href="{{ route('catalog.index', ['category' => 'kamera']) }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Kamera Mirrorless</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'lensa']) }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Lensa Premium</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'drone']) }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Drone & Aksesoris</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'lighting']) }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Lighting & Flash</a></li>
                    </ul>
                </div>

                <!-- Col 3: Kategori Camping -->
                <div>
                    <h4 class="font-display font-bold text-white text-sm uppercase tracking-wider mb-5">Peralatan Camping</h4>
                    <ul class="flex flex-col gap-3 text-sm">
                        <li><a href="{{ route('catalog.index', ['category' => 'tenda']) }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Tenda Dome Premium</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'carrier']) }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Tas Carrier Gunung</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'sleeping-bag']) }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Sleeping Bag Hangat</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'cooking-set']) }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Cooking Outdoor Set</a></li>
                    </ul>
                </div>

                <!-- Col 4: Informasi & Bantuan -->
                <div>
                    <h4 class="font-display font-bold text-white text-sm uppercase tracking-wider mb-5">Dukungan</h4>
                    <ul class="flex flex-col gap-3 text-sm">
                        <li><a href="{{ route('about') }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Hubungi Kami</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">Kebijakan Privasi</a></li>
                    </ul>
                </div>

            </div>

            <hr class="border-slate-900 my-8">

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500 text-center sm:text-left">
                    &copy; {{ date('Y') }} EquipRent. Semua hak cipta dilindungi undang-undang.
                </p>
                <div class="flex items-center gap-4 text-xs text-slate-600">
                    <span>Terintegrasi dengan:</span>
                    <span class="font-semibold text-slate-400 tracking-wider">MIDTRANS</span>
                    <span>|</span>
                    <span class="font-semibold text-slate-400 tracking-wider">WHATSAPP SECURE</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
