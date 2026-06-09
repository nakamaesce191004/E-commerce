@extends('layouts.frontend')

@section('title', 'Pusat Rental Kamera & Alat Camping Premium')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[85vh] flex items-center justify-center overflow-hidden bg-slate-950 pt-16">
    <!-- Visual background gradient glow -->
    <div class="absolute inset-0 z-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-950/30 via-slate-950 to-slate-950"></div>
    <div class="absolute top-20 left-10 w-72 h-72 rounded-full bg-emerald-500/5 blur-3xl"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 rounded-full bg-orange-500/5 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Content -->
            <div class="lg:col-span-7 flex flex-col items-start text-left">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 text-emerald-400 font-semibold text-xs tracking-wider uppercase mb-6 border border-emerald-500/20">
                    <i data-lucide="sparkles" class="h-3.5 w-3.5"></i> Sewa Premium, Petualangan Instan
                </span>
                
                <h1 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-[1.1] mb-6">
                    Tangkap Momen & Taklukkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Puncak Tertinggi</span>
                </h1>
                
                <p class="text-lg text-slate-300 max-w-xl mb-8 leading-relaxed">
                    Sistem persewaan kamera profesional dan alat camping outdoor terlengkap. Dapatkan kualitas gear terbaik yang steril, terawat, dan berkinerja tinggi hanya dalam hitungan menit.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                    <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-emerald-500 text-slate-950 hover:bg-emerald-400 transition-all text-center hover-glow-emerald shadow-lg shadow-emerald-500/10 flex items-center justify-center gap-2">
                        Sewa Sekarang <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </a>
                    <a href="#featured-gear" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-slate-900 border border-slate-800 text-white hover:bg-slate-800 transition-all text-center flex items-center justify-center gap-2">
                        Lihat Produk <i data-lucide="binoculars" class="h-5 w-5"></i>
                    </a>
                </div>

                <!-- Stats Badges -->
                <div class="grid grid-cols-3 gap-6 sm:gap-10 mt-12 pt-8 border-t border-slate-900 w-full">
                    <div>
                        <p class="font-display font-extrabold text-3xl text-white">50+</p>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mt-1">Gear Premium</p>
                    </div>
                    <div>
                        <p class="font-display font-extrabold text-3xl text-white">1.2k+</p>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mt-1">Sewa Sukses</p>
                    </div>
                    <div>
                        <p class="font-display font-extrabold text-3xl text-white">99%</p>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mt-1">Review Puas</p>
                    </div>
                </div>
            </div>

            <!-- Right Visual Banner -->
            <div class="lg:col-span-5 relative">
                <!-- Stacked graphic illustration -->
                <div class="relative z-10 w-full aspect-square rounded-3xl overflow-hidden glass border border-slate-800 p-8 flex flex-col justify-between shadow-2xl bg-gradient-to-br from-slate-900 to-slate-950">
                    <div class="flex justify-between items-start">
                        <span class="p-3 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <i data-lucide="camera" class="h-6 w-6"></i>
                        </span>
                        <span class="px-3 py-1 rounded-full bg-slate-900 text-xs font-semibold text-slate-400 border border-slate-800 flex items-center gap-1">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Ready Stock
                        </span>
                    </div>
                    
                    <div class="my-auto py-8">
                        <p class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-2">HOT LEASE ITEM</p>
                        <h3 class="font-display font-extrabold text-2xl text-white mb-2">Sony Alpha 7 IV</h3>
                        <p class="text-sm text-slate-400 mb-4 line-clamp-2">Kamera hybrid 33 Megapixel favorit kreator konten profesional & sinematografer.</p>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-2xl font-bold text-white">Rp 350.000</span>
                            <span class="text-xs text-slate-500">/ Hari</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-900 pt-6">
                        <div class="flex -space-x-2">
                            <div class="h-8 w-8 rounded-full border-2 border-slate-950 bg-slate-800 flex items-center justify-center text-[10px] font-bold text-emerald-400">AJ</div>
                            <div class="h-8 w-8 rounded-full border-2 border-slate-950 bg-slate-800 flex items-center justify-center text-[10px] font-bold text-orange-400">MD</div>
                            <div class="h-8 w-8 rounded-full border-2 border-slate-950 bg-slate-800 flex items-center justify-center text-[10px] font-bold text-blue-400 font-mono">+9</div>
                        </div>
                        <a href="{{ route('catalog.show', 'sony-a7iv-body') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-emerald-400 flex items-center gap-1.5 border border-slate-800 transition-colors">
                            Lihat Detail <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Secondary decorative background box -->
                <div class="absolute -bottom-6 -right-6 -z-10 w-full aspect-square rounded-3xl bg-slate-900 border border-slate-800 opacity-60"></div>
            </div>

        </div>
    </div>
</section>

<!-- Features Info Grid -->
<section class="py-10 bg-slate-950 border-y border-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex gap-4 p-5 rounded-2xl bg-slate-900/40 border border-slate-900 hover:border-slate-800 transition-colors">
                <span class="p-3 h-12 w-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="shield-check" class="h-6 w-6"></i>
                </span>
                <div>
                    <h3 class="font-bold text-white mb-1">Gear 100% Steril & Prima</h3>
                    <p class="text-sm text-slate-400">Semua peralatan dibersihkan, didesinfeksi, dan dicek berkala sebelum diserahkan ke penyewa.</p>
                </div>
            </div>
            <div class="flex gap-4 p-5 rounded-2xl bg-slate-900/40 border border-slate-900 hover:border-slate-800 transition-colors">
                <span class="p-3 h-12 w-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="calendar" class="h-6 w-6"></i>
                </span>
                <div>
                    <h3 class="font-bold text-white mb-1">Bebas Bentrok Jadwal</h3>
                    <p class="text-sm text-slate-400">Sistem kalender real-time pintar kami menjamin tidak ada tumpang tindih sewa pada tanggal yang sama.</p>
                </div>
            </div>
            <div class="flex gap-4 p-5 rounded-2xl bg-slate-900/40 border border-slate-900 hover:border-slate-800 transition-colors">
                <span class="p-3 h-12 w-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="credit-card" class="h-6 w-6"></i>
                </span>
                <div>
                    <h3 class="font-bold text-white mb-1">Kemudahan Pembayaran</h3>
                    <p class="text-sm text-slate-400">Integrasi transfer bank manual instan dan online gateway modern (Midtrans VA & e-Wallet).</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Categories Shortcut -->
<section class="py-24 bg-slate-950 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-white mb-4">Pilih Kategori Kebutuhan Anda</h2>
            <p class="text-slate-400">Temukan perlengkapan petualangan outdoor atau produksi video premium terbaik Anda dengan filter kategori cepat kami.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
            @foreach($categories as $category)
                <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" 
                   class="group p-6 rounded-2xl bg-slate-900/50 border border-slate-900 hover:border-emerald-500/30 text-center hover:bg-slate-900 transition-all hover:-translate-y-1">
                    <span class="inline-flex p-4 rounded-2xl bg-slate-950 text-slate-400 group-hover:text-emerald-400 group-hover:bg-slate-950 transition-colors shadow-inner mb-4">
                        <!-- Map matching icons dynamically based on db data -->
                        <i data-lucide="{{ $category->icon ?? 'box' }}" class="h-6 w-6"></i>
                    </span>
                    <h3 class="font-display font-bold text-sm text-white group-hover:text-emerald-400 transition-colors">{{ $category->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">{{ $category->type === 'camera' ? 'Kamera & Studio' : 'Outdoor & Camp' }}</p>
                </a>
            @endforeach
        </div>

    </div>
</section>

<!-- Featured Products (featured-gear) -->
<section id="featured-gear" class="py-24 bg-slate-950 relative border-t border-slate-900">
    <div class="absolute inset-0 z-0 bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-orange-950/10 via-slate-950 to-slate-950"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-16 gap-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 mb-2 block">HIGHLIGHT INVENTARIS</span>
                <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-white">Perlengkapan Paling Populer</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1 text-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors group">
                Lihat Katalog Lengkap <i data-lucide="arrow-right" class="h-4 w-4 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($featuredProducts as $product)
                <!-- Product Card -->
                <div class="group rounded-2xl bg-slate-900/30 border border-slate-900 hover:border-slate-800 transition-all flex flex-col justify-between overflow-hidden relative hover-glow-emerald bg-gradient-to-b from-slate-900/50 to-slate-950">
                    
                    <!-- Availability Badge -->
                    <span class="absolute top-4 left-4 z-10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-slate-950/90 text-emerald-400 border border-emerald-500/20">
                        {{ $product->category->name }}
                    </span>

                    <!-- Product Image -->
                    <a href="{{ route('catalog.show', $product->slug) }}" class="block aspect-[4/3] w-full overflow-hidden bg-slate-950 border-b border-slate-900 relative">
                        @if($product->thumbnail_url)
                            <img src="{{ asset($product->thumbnail_url) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                <i data-lucide="{{ $product->category->type === 'camera' ? 'camera' : 'tent' }}" class="h-12 w-12 text-slate-700 group-hover:scale-110 group-hover:text-emerald-500 transition-all duration-300"></i>
                            </div>
                        @endif
                    </a>

                    <!-- Details -->
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <!-- Star Rating -->
                            <div class="flex items-center gap-1.5 mb-2.5">
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
                            <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed mb-3">
                                {{ $product->description }}
                            </p>

                            <!-- Stock & Denda Info -->
                            <div class="grid grid-cols-2 gap-2 mb-4 p-2 bg-slate-950/40 rounded-lg border border-slate-900 text-[10px]">
                                <div class="flex items-center gap-1.5 text-slate-400">
                                    <i data-lucide="package" class="h-3.5 w-3.5 text-emerald-500"></i>
                                    <span>Stok: <strong class="text-slate-200">{{ $product->stock }} unit</strong></span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-400">
                                    <i data-lucide="alert-triangle" class="h-3.5 w-3.5 text-rose-500"></i>
                                    <span>Denda: <strong class="text-rose-400">Rp{{ number_format($product->denda_per_day, 0, ',', '.') }}/h</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-slate-900 pt-4 mt-auto">
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase font-semibold">Harga Sewa</p>
                                <div class="flex items-baseline gap-0.5">
                                    <span class="text-sm font-extrabold text-emerald-400">Rp {{ number_format($product->price_per_day, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-slate-500">/hari</span>
                                </div>
                            </div>
                            <a href="{{ route('catalog.show', $product->slug) }}" class="p-2.5 rounded-xl bg-slate-950 text-slate-300 hover:bg-emerald-500 hover:text-slate-950 border border-slate-800 hover:border-emerald-500 transition-all" title="Detail Perlengkapan">
                                <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-4 text-center py-12">
                    <p class="text-slate-500">Belum ada produk unggulan yang tersedia.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>

<!-- Testimonials Section -->
<section class="py-24 bg-slate-950 relative border-t border-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 mb-2 block">REPUTASI KAMI</span>
            <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-white mb-4">Apa Kata Pelanggan Kami?</h2>
            <p class="text-slate-400">Ribuan petualang dan konten kreator telah menggunakan layanan persewaan gear kami. Berikut adalah pengalaman mereka.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Review 1 -->
            <div class="p-6 rounded-2xl bg-slate-900/30 border border-slate-900 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-500 gap-0.5 mb-4">
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                    </div>
                    <p class="text-sm text-slate-300 italic leading-relaxed mb-6">
                        "Sewa kamera Sony A7IV kemarin buat kebutuhan prewedding klien. Kondisi kamera benar-benar bersih seperti baru, sensor cling, dan baterai awet. Proses serah terima sangat cepat. Recomended bgt!"
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-emerald-500/10 text-emerald-400 font-bold flex items-center justify-center text-sm">
                        RD
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Rian Dwi</h4>
                        <p class="text-xs text-slate-500">Fotografer Prewedding, Bandung</p>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="p-6 rounded-2xl bg-slate-900/30 border border-slate-900 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-500 gap-0.5 mb-4">
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                    </div>
                    <p class="text-sm text-slate-300 italic leading-relaxed mb-6">
                        "Pertama kali coba sewa alat camping tenda Naturehike disini buat naik Gunung Gede Pangrango. Tendanya waterproof total pas hujan lebat di pos bayangan. Sangat puas dengan kebersihan alatnya!"
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-emerald-500/10 text-emerald-400 font-bold flex items-center justify-center text-sm">
                        AN
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Agung Nugroho</h4>
                        <p class="text-xs text-slate-500">Pecinta Alam, Jakarta</p>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="p-6 rounded-2xl bg-slate-900/30 border border-slate-900 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-500 gap-0.5 mb-4">
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                        <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-slate-700"></i>
                    </div>
                    <p class="text-sm text-slate-300 italic leading-relaxed mb-6">
                        "Layanan sewa drone DJI Mavic 3 disini sangat menolong proyek dokumentasi perkebunan saya. Harganya bersahabat, drone sudah dikalibrasi, dan kelengkapan baterainya mantap. Mantap betul!"
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-emerald-500/10 text-emerald-400 font-bold flex items-center justify-center text-sm">
                        LM
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Lina Marlina</h4>
                        <p class="text-xs text-slate-500">Videografer Korporat, Surabaya</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Call To Action -->
<section class="py-20 bg-slate-950 relative border-t border-slate-900 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/10 to-transparent"></div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center glass border border-slate-800 p-12 sm:p-16 rounded-3xl glow-emerald">
        
        <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-white tracking-tight mb-4">
            Sudah Siap Memulai Petualangan Anda?
        </h2>
        <p class="text-base text-slate-300 max-w-xl mx-auto mb-8 leading-relaxed">
            Dapatkan diskon 10% untuk transaksi sewa pertama Anda dengan mendaftar akun baru hari ini. Sewa kamera canggih atau alat camping premium Anda sekarang juga!
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-emerald-500 text-slate-950 hover:bg-emerald-400 hover-glow-emerald transition-all text-center">
                Daftar Akun Gratis
            </a>
            <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-slate-900 border border-slate-800 text-white hover:bg-slate-800 transition-all text-center flex items-center justify-center gap-2">
                Jelajahi Katalog <i data-lucide="binoculars" class="h-5 w-5"></i>
            </a>
        </div>

    </div>
</section>
@endsection
