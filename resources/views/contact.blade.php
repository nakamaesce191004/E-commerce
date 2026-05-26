@extends('layouts.frontend')

@section('title', 'Hubungi Kami - Layanan Pelanggan 24/7')

@section('content')
<!-- Header Banner -->
<section class="relative py-20 bg-slate-950 overflow-hidden border-b border-slate-900">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-emerald-950/20 via-slate-950 to-slate-950"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 mb-3 block">HUBUNGI KAMI</span>
        <h1 class="font-display font-extrabold text-4xl sm:text-5xl text-white tracking-tight mb-6">
            Ada Pertanyaan? <span class="text-emerald-500">Kami Siap Membantu</span>
        </h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Tim layanan pelanggan kami siap merespon pertanyaan Anda mengenai detail spesifikasi alat, ketersediaan, ataupun opsi pengiriman khusus.
        </p>
    </div>
</section>

<!-- Contact Form & Info Grid -->
<section class="py-24 bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            
            <!-- Left Info Panel -->
            <div class="lg:col-span-5 flex flex-col justify-between">
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white mb-6">Informasi Kontak & Jam Operasional</h2>
                    <p class="text-sm text-slate-400 leading-relaxed mb-8">
                        Silakan hubungi kami melalui saluran berikut untuk respon yang lebih cepat, atau datangi langsung toko kami untuk melihat unit peralatan sewa secara langsung.
                    </p>
                    
                    <div class="space-y-6">
                        <!-- Phone / WA -->
                        <div class="flex items-start gap-4">
                            <span class="p-3 rounded-xl bg-slate-900 text-emerald-400 border border-slate-800 flex-shrink-0">
                                <i data-lucide="phone" class="h-5 w-5"></i>
                            </span>
                            <div>
                                <h4 class="font-bold text-white text-sm mb-0.5">WhatsApp / Telepon</h4>
                                <p class="text-sm text-slate-300 font-mono">+62 812-3456-7890</p>
                                <p class="text-xs text-slate-500">Respon cepat, Senin - Minggu (08:00 - 21:00)</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <span class="p-3 rounded-xl bg-slate-900 text-emerald-400 border border-slate-800 flex-shrink-0">
                                <i data-lucide="mail" class="h-5 w-5"></i>
                            </span>
                            <div>
                                <h4 class="font-bold text-white text-sm mb-0.5">Surel / Email Resmi</h4>
                                <p class="text-sm text-slate-300 font-mono">support@equiprent.com</p>
                                <p class="text-xs text-slate-500">Untuk proposal kerja sama korporasi dan kemitraan</p>
                            </div>
                        </div>

                        <!-- Office Location -->
                        <div class="flex items-start gap-4">
                            <span class="p-3 rounded-xl bg-slate-900 text-emerald-400 border border-slate-800 flex-shrink-0">
                                <i data-lucide="map-pin" class="h-5 w-5"></i>
                            </span>
                            <div>
                                <h4 class="font-bold text-white text-sm mb-0.5">Alamat Fisik Toko</h4>
                                <p class="text-sm text-slate-300">HQ EquipRent, Kebayoran Baru, Jakarta Selatan, 12110</p>
                                <p class="text-xs text-slate-500">Seberang stasiun MRT, parkir luas & aman</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Open times -->
                <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-900 mt-10">
                    <h4 class="font-bold text-white text-sm mb-3 flex items-center gap-1.5">
                        <i data-lucide="clock" class="h-4 w-4 text-emerald-400"></i> Jam Kerja Showroom
                    </h4>
                    <div class="grid grid-cols-2 gap-2 text-xs text-slate-400">
                        <p>Senin - Jumat:</p>
                        <p class="font-semibold text-slate-200">08:00 - 20:00 WIB</p>
                        <p>Sabtu - Minggu:</p>
                        <p class="font-semibold text-slate-200">07:00 - 21:00 WIB</p>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-7">
                <div class="p-8 rounded-3xl bg-slate-900/20 border border-slate-900 glass">
                    <h3 class="font-display font-bold text-xl text-white mb-6">Kirim Pesan Instan</h3>
                    
                    <form action="#" method="POST" class="space-y-6" onsubmit="event.preventDefault(); alert('Terima kasih! Pesan Anda telah terkirim secara simulasi. Admin akan segera menghubungi Anda kembali.');">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" id="name" required placeholder="Budi Santoso" 
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-all">
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Alamat Email</label>
                                <input type="email" id="email" required placeholder="budi@example.com" 
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Subjek Pesan</label>
                            <input type="text" id="subject" required placeholder="Tanya detail sewa drone DJI" 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-all">
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Isi Pesan Anda</label>
                            <textarea id="message" rows="5" required placeholder="Halo Admin, saya ingin menanyakan..." 
                                      class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-all resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl font-bold bg-emerald-500 text-slate-950 hover:bg-emerald-400 hover-glow-emerald transition-all flex items-center justify-center gap-2">
                            Kirim Pesan <i data-lucide="send" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
