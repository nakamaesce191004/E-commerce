@extends('layouts.frontend')

@section('title', 'Katalog Perlengkapan Sewa Kamera & Outdoor Gear')

@section('content')
<section class="py-12 bg-slate-950 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Catalog Header -->
        <div class="border-b border-slate-900 pb-8 mb-10">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 mb-2 block">TEMUKAN GEAR TERBAIK</span>
            <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-white">Katalog Peralatan Sewa</h1>
            <p class="text-sm text-slate-400 mt-2">Filter perlengkapan kamera kelas dunia dan alat camping outdoor sesuai kebutuhan petualangan atau produksi Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start" x-data="{ mobileFiltersOpen: false }">
            
            <!-- 1. Filters Sidebar (Desktop) -->
            <aside class="hidden lg:block space-y-8 glass p-6 rounded-2xl border border-slate-900 bg-slate-900/10">
                <form action="{{ route('catalog.index') }}" method="GET" class="space-y-6">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Pencarian Alat</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Kamera, tenda, drone..." 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
                            <i data-lucide="search" class="absolute left-3.5 top-3 h-4 w-4 text-slate-500"></i>
                        </div>
                    </div>

                    <!-- Category Quick Select -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Kategori</label>
                        <div class="flex flex-col gap-2">
                            <a href="{{ url()->current() . '?' . http_build_query(request()->except(['category', 'page'])) }}" 
                               class="flex items-center justify-between px-3 py-2 text-xs rounded-lg transition-all {{ !request('category') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white border border-transparent' }}">
                                <span>Semua Kategori</span>
                                <i data-lucide="chevron-right" class="h-3 w-3"></i>
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except('page'), ['category' => $cat->slug])) }}" 
                                   class="flex items-center justify-between px-3 py-2 text-xs rounded-lg transition-all {{ request('category') === $cat->slug ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white border border-transparent' }}">
                                    <span class="flex items-center gap-2">
                                        <i data-lucide="{{ $cat->icon ?? 'box' }}" class="h-3.5 w-3.5"></i>
                                        {{ $cat->name }}
                                    </span>
                                    <i data-lucide="chevron-right" class="h-3 w-3"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Availability Switch -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Ketersediaan</label>
                        <label class="flex items-center gap-2.5 cursor-pointer group text-xs text-slate-300 hover:text-white transition-colors">
                            <input type="checkbox" name="status" value="available" {{ request('status') === 'available' ? 'checked' : '' }} 
                                   class="rounded bg-slate-950 border-slate-800 text-emerald-500 focus:ring-emerald-500/20 focus:ring-offset-slate-900 h-4 w-4">
                            Hanya yang tersedia
                        </label>
                    </div>

                    <!-- Price Limit -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Harga / Hari (IDR)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                        </div>
                    </div>

                    <!-- Sorting -->
                    <div>
                        <label for="sort_by" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Urutkan</label>
                        <select name="sort_by" id="sort_by" class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:ring-emerald-500">
                            <option value="popular" {{ request('sort_by') === 'popular' ? 'selected' : '' }}>Terpopuler / Rekomendasi</option>
                            <option value="price_low" {{ request('sort_by') === 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tinggi</option>
                            <option value="price_high" {{ request('sort_by') === 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Rendah</option>
                        </select>
                    </div>

                    <!-- Submit & Clear Buttons -->
                    <div class="pt-4 grid grid-cols-2 gap-3 border-t border-slate-900">
                        <a href="{{ route('catalog.index') }}" class="py-2.5 text-center text-xs font-semibold border border-slate-800 hover:bg-slate-900 rounded-xl text-slate-300 hover:text-white transition-all">Clear</a>
                        <button type="submit" class="py-2.5 bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 rounded-xl text-xs transition-all hover-glow-emerald">Apply</button>
                    </div>
                </form>
            </aside>

            <!-- 2. Products List Grid Area -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- Filters Info Top & Mobile Button -->
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-slate-900/40 border border-slate-900">
                    <p class="text-xs text-slate-400">
                        Menampilkan <span class="text-white font-bold">{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</span> dari <span class="text-emerald-400 font-bold">{{ $products->total() }}</span> perlengkapan
                    </p>
                    
                    <div class="flex items-center gap-3">
                        <!-- Mobile Filters Trigger -->
                        <button @click="mobileFiltersOpen = true" class="lg:hidden px-3 py-2 text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-800 rounded-lg flex items-center gap-1.5 transition-colors">
                            <i data-lucide="filter" class="h-3.5 w-3.5"></i> Filters
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse($products as $product)
                        <!-- Catalog Product Card -->
                        <div class="group rounded-2xl bg-slate-900/30 border border-slate-900 hover:border-slate-800 transition-all flex flex-col justify-between overflow-hidden relative hover-glow-emerald bg-gradient-to-b from-slate-900/50 to-slate-950">
                            
                            <!-- Category Tag badge -->
                            <span class="absolute top-4 left-4 z-10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-slate-950/90 text-emerald-400 border border-emerald-500/20">
                                {{ $product->category->name }}
                            </span>

                            <!-- Status Badge -->
                            <span class="absolute top-4 right-4 z-10 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-md {{ $product->status === 'available' ? 'bg-emerald-950/80 text-emerald-400 border border-emerald-500/20' : 'bg-orange-950/80 text-orange-400 border border-orange-500/20' }}">
                                {{ $product->status === 'available' ? 'Tersedia' : 'Disewa/Maint.' }}
                            </span>

                            <!-- Thumbnail -->
                            <a href="{{ route('catalog.show', $product->slug) }}" class="block aspect-[4/3] w-full overflow-hidden bg-slate-950 border-b border-slate-900 relative">
                                @if($product->thumbnail_url)
                                    <img src="{{ asset($product->thumbnail_url) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                        <i data-lucide="{{ $product->category->type === 'camera' ? 'camera' : 'tent' }}" class="h-12 w-12 text-slate-700 group-hover:scale-110 group-hover:text-emerald-500 transition-all duration-300"></i>
                                    </div>
                                @endif
                            </a>

                            <!-- Card details -->
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <!-- Ratings -->
                                    <div class="flex items-center gap-1.5 mb-2">
                                        <div class="flex items-center text-amber-500">
                                            <i data-lucide="star" class="h-3.5 w-3.5 fill-amber-500"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-300">{{ $product->rating }}</span>
                                        <span class="text-xs text-slate-500">({{ $product->reviews()->count() }} ulasan)</span>
                                    </div>

                                    <a href="{{ route('catalog.show', $product->slug) }}" class="block">
                                        <h3 class="font-display font-bold text-base text-white hover:text-emerald-400 transition-colors line-clamp-1 mb-2">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed mb-4">
                                        {{ $product->description }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between border-t border-slate-900 pt-4 mt-auto">
                                    <div>
                                        <p class="text-[10px] text-slate-500 uppercase font-semibold">Harga Sewa</p>
                                        <div class="flex items-baseline gap-0.5">
                                            <span class="text-sm font-extrabold text-emerald-400">Rp {{ number_format($product->price_per_day, 0, ',', '.') }}</span>
                                            <span class="text-[10px] text-slate-500">/hari</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('catalog.show', $product->slug) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-950 text-emerald-400 border border-slate-800 hover:bg-emerald-500 hover:text-slate-950 hover:border-emerald-500 transition-all flex items-center gap-1.5">
                                        Detail <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="col-span-3 text-center py-20 bg-slate-900/20 border border-slate-900 rounded-2xl glass p-8">
                            <i data-lucide="binoculars" class="h-10 w-10 text-slate-600 mx-auto mb-4"></i>
                            <h3 class="font-bold text-white mb-2">Gear Tidak Ditemukan</h3>
                            <p class="text-sm text-slate-400 max-w-sm mx-auto mb-6">Maaf, kami tidak dapat menemukan peralatan sewa yang sesuai dengan kriteria filter pencarian Anda.</p>
                            <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 text-xs">Reset Semua Filter</a>
                        </div>
                    @endforelse
                </div>

                <!-- Custom Elegant Pagination links -->
                @if($products->hasPages())
                    <div class="pt-6 border-t border-slate-900">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

    <!-- Mobile Filters Overlay / Drawer -->
    <div x-show="mobileFiltersOpen" class="fixed inset-0 z-50 lg:hidden overflow-hidden" style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="mobileFiltersOpen = false"></div>

        <!-- Panel drawer -->
        <div class="absolute inset-y-0 right-0 max-w-xs w-full bg-slate-900 shadow-2xl p-6 flex flex-col glass border-l border-slate-800">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-6">
                <h3 class="font-display font-bold text-white text-base flex items-center gap-2">
                    <i data-lucide="filter" class="h-4 w-4 text-emerald-400"></i> Filter Pencarian
                </h3>
                <button @click="mobileFiltersOpen = false" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <!-- Form copy inside drawer -->
            <form action="{{ route('catalog.index') }}" method="GET" class="flex-grow overflow-y-auto space-y-6 pr-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <!-- Availability check -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Ketersediaan</label>
                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-slate-300 hover:text-white">
                        <input type="checkbox" name="status" value="available" {{ request('status') === 'available' ? 'checked' : '' }} 
                               class="rounded bg-slate-950 border-slate-800 text-emerald-500 focus:ring-emerald-500/20 h-4 w-4">
                        Hanya yang tersedia
                    </label>
                </div>

                <!-- Price range -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Harga / Hari (IDR)</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                    </div>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort_by_m" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Urutkan</label>
                    <select name="sort_by" id="sort_by_m" class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                        <option value="popular" {{ request('sort_by') === 'popular' ? 'selected' : '' }}>Terpopuler / Rekomendasi</option>
                        <option value="price_low" {{ request('sort_by') === 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tinggi</option>
                        <option value="price_high" {{ request('sort_by') === 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Rendah</option>
                    </select>
                </div>

                <div class="pt-6 grid grid-cols-2 gap-3 border-t border-slate-800 mt-auto">
                    <a href="{{ route('catalog.index') }}" class="py-2.5 text-center text-xs font-semibold border border-slate-800 hover:bg-slate-950 rounded-xl text-slate-300 hover:text-white transition-all">Clear</a>
                    <button type="submit" class="py-2.5 bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 rounded-xl text-xs transition-all">Apply</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
