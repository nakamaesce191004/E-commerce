@extends('layouts.frontend')

@section('title', 'Tentang Kami - Pionir Sewa Kamera & Outdoor Gear')

@section('content')
<!-- Header Banner -->
<section class="relative py-20 bg-slate-950 overflow-hidden border-b border-slate-900">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-emerald-950/20 via-slate-950 to-slate-950"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 mb-3 block">SIAPA KAMI</span>
        <h1 class="font-display font-extrabold text-4xl sm:text-5xl text-white tracking-tight mb-6">
            Mendukung Kreativitas & <span class="text-emerald-500">Jiwa Petualang Anda</span>
        </h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
            EquipRent hadir sebagai platform modern penyedia jasa persewaan kamera kelas sinematik dan peralatan outdoor camping premium terlengkap demi mewujudkan momen epik Anda.
        </p>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-24 bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            
            <!-- Left Info -->
            <div class="lg:col-span-6">
                <h2 class="font-display font-extrabold text-3xl text-white mb-6">Dari Hobi Menjadi Solusi Utama Peminjaman Gear Indonesia</h2>
                <div class="space-y-4 text-sm text-slate-300 leading-relaxed">
                    <p>
                        Didirikan pada tahun 2023, EquipRent berawal dari keresahan para fotografer pemula dan pendaki gunung yang kesulitan mendapatkan akses ke peralatan premium berbiaya tinggi. Membeli gear profesional seringkali tidak ekonomis bagi mereka yang hanya memakainya sesekali.
                    </p>
                    <p>
                        Kami melihat kesempatan ini dan merancang sebuah platform digital canggih yang mempertemukan kebutuhan persewaan berkualitas dengan verifikasi yang andal. Kami berinvestasi langsung pada inventaris berkualitas tinggi, memastikan standard perawatan (sterilisasi & kalibrasi) berada pada level tertinggi.
                    </p>
                    <p>
                        Hari ini, kami bangga telah melayani lebih dari seribu pelanggan di seluruh Jabodetabek dan Bandung, mulai dari fotografer pernikahan, korporasi besar, hingga petualang gunung yang menaklukkan puncak-puncak tertinggi Indonesia.
                    </p>
                </div>
            </div>

            <!-- Right Info Cards -->
            <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-900">
                    <span class="inline-flex p-3 rounded-xl bg-emerald-500/10 text-emerald-400 mb-4">
                        <i data-lucide="shield" class="h-6 w-6"></i>
                    </span>
                    <h3 class="font-bold text-white mb-2 text-base">Sterilisasi Ketat</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Setiap kantong tidur, tenda, lensa, dan bodi kamera melalui pembersihan desinfektan ultrasonik dan UV-C pasca pemakaian.</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-900">
                    <span class="inline-flex p-3 rounded-xl bg-emerald-500/10 text-emerald-400 mb-4">
                        <i data-lucide="zap" class="h-6 w-6"></i>
                    </span>
                    <h3 class="font-bold text-white mb-2 text-base">Instan Booking</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Sistem web modern yang responsif memudahkan Anda mengecek ketersediaan tanggal langsung dan membayar online dengan aman.</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-900">
                    <span class="inline-flex p-3 rounded-xl bg-emerald-500/10 text-emerald-400 mb-4">
                        <i data-lucide="heart" class="h-6 w-6"></i>
                    </span>
                    <h3 class="font-bold text-white mb-2 text-base">Kondisi Prima</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Semua lensa dikalibrasi fokusnya, drone diuji sensor tabraknya, dan tenda dicoba kekencangan flysheet-nya berkala.</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-900">
                    <span class="inline-flex p-3 rounded-xl bg-emerald-500/10 text-emerald-400 mb-4">
                        <i data-lucide="users" class="h-6 w-6"></i>
                    </span>
                    <h3 class="font-bold text-white mb-2 text-base">Dukungan Komunitas</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Kami aktif mengadakan lokakarya fotografi luar ruangan gratis dan kamp bersama pecinta alam tiap kuartal.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Values Pillars -->
<section class="py-24 bg-slate-900/20 border-t border-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 mb-2 block">KOMITMEN KAMI</span>
            <h2 class="font-display font-extrabold text-3xl text-white">Prinsip Utama Layanan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="text-center p-4">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-emerald-500/10 text-emerald-400 mb-6 font-display font-extrabold text-xl">1</div>
                <h3 class="font-bold text-white text-lg mb-3">Kepercayaan Pelanggan</h3>
                <p class="text-sm text-slate-400 leading-relaxed">Kepercayaan adalah mata uang kami. Kami berkomitmen menyajikan gear dengan kondisi 100% sesuai dengan spesifikasi yang tertera di website, tanpa tipu-tipu.</p>
            </div>
            <div class="text-center p-4">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-emerald-500/10 text-emerald-400 mb-6 font-display font-extrabold text-xl">2</div>
                <h3 class="font-bold text-white text-lg mb-3">Harga Terjangkau</h3>
                <p class="text-sm text-slate-400 leading-relaxed">Kami terus mengoptimalkan rantai pasok dan pemeliharaan internal agar dapat menekan biaya sewa harian se-kompetitif mungkin demi mendukung hobi Anda.</p>
            </div>
            <div class="text-center p-4">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-emerald-500/10 text-emerald-400 mb-6 font-display font-extrabold text-xl">3</div>
                <h3 class="font-bold text-white text-lg mb-3">Konservasi Alam</h3>
                <p class="text-sm text-slate-400 leading-relaxed">Setiap transaksi rental peralatan outdoor camping Anda menyumbang Rp 5.000 untuk program penanaman pohon di kawasan konservasi gunung Jawa Barat.</p>
            </div>
        </div>

    </div>
</section>
@endsection
