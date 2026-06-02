@extends('layouts.frontend')

@section('title', 'Checkout Pemesanan Rental')

@section('content')
<section class="py-12 bg-slate-950 min-h-[75vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Headers -->
        <div class="border-b border-slate-900 pb-6 mb-10">
            <h1 class="font-display font-extrabold text-3xl text-white flex items-center gap-3">
                <i data-lucide="check-square" class="text-emerald-400"></i> Checkout Pemesanan
            </h1>
            <p class="text-xs text-slate-500 mt-2">Lengkapi informasi pengiriman dan pilih metode pembayaran untuk menyelesaikan proses sewa.</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            @csrf
            
            <!-- Form Inputs (Left) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Section 1: Customer Details -->
                <div class="p-8 rounded-2xl bg-slate-900/20 border border-slate-900 glass space-y-6">
                    <h3 class="font-display font-bold text-white text-base flex items-center gap-2 border-b border-slate-800 pb-3">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-bold font-mono">1</span>
                        Detail Kontak & Alamat Pengiriman
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" disabled value="{{ $user->name }}" 
                                   class="w-full bg-slate-950 border border-slate-900 rounded-xl px-4 py-3 text-xs text-slate-500 focus:outline-none cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Alamat Email</label>
                            <input type="email" disabled value="{{ $user->email }}" 
                                   class="w-full bg-slate-950 border border-slate-900 rounded-xl px-4 py-3 text-xs text-slate-500 focus:outline-none cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nomor WhatsApp Aktif</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="Contoh: 081234567890" 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500 @error('phone') border-orange-500 @enderror">
                            @error('phone')
                                <p class="text-xs text-orange-500 mt-1.5">{{ $message }}</p>
                            @enderror
                            <p class="text-[10px] text-slate-500 mt-1.5">Digunakan untuk konfirmasi pengiriman barang dan koordinasi pengembalian via WhatsApp.</p>
                        </div>

                        <div>
                            <label for="shipping_address" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Alamat Lengkap Pengiriman / Pengambilan</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3" required placeholder="Jl. Merdeka No. 10, RT 02/RW 03, Kel. Kebayoran, Jakarta Selatan" 
                                      class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500 resize-none @error('shipping_address') border-orange-500 @enderror">{{ old('shipping_address', $user->address) }}</textarea>
                            @error('shipping_address')
                                <p class="text-xs text-orange-500 mt-1.5">{{ $message }}</p>
                            @enderror
                            <p class="text-[10px] text-slate-500 mt-1.5">Tulis alamat lengkap pengiriman gear ke lokasi Anda atau ketik "AMBIL DI TOKO" jika ingin mengambil sendiri.</p>
                        </div>

                        <div>
                            <label for="note" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                            <input type="text" id="note" name="note" value="{{ old('note') }}" placeholder="Contoh: Tolong kirim sebelum jam 9 pagi atau baterai kamera mohon dicharge penuh." 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Payment Method Choices -->
                <div class="p-8 rounded-2xl bg-slate-900/20 border border-slate-900 glass space-y-6">
                    <h3 class="font-display font-bold text-white text-base flex items-center gap-2 border-b border-slate-800 pb-3">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-bold font-mono">2</span>
                        Metode Pembayaran
                    </h3>

                    <input type="hidden" name="payment_method" value="midtrans">

                    <div class="p-5 rounded-2xl border border-emerald-500 bg-emerald-500/5">
                        <div class="flex items-start gap-4">
                            <div class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400">
                                <i data-lucide="credit-card" class="h-4 w-4"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-sm mb-1">Midtrans Secure Pay</h4>
                                <p class="text-[10px] text-slate-300 leading-relaxed">Pembayaran dilakukan melalui gateway Midtrans (simulasi sandbox). Admin akan memverifikasi status transaksi dan ketersediaan barang sebelum pesanan diproses.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Order Summary Card (Right) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 glass space-y-6">
                    <h3 class="font-display font-bold text-white text-base border-b border-slate-800 pb-3">Ringkasan Item Sewa</h3>
                    
                    <div class="divide-y divide-slate-900 space-y-4">
                        @foreach($cart as $item)
                            <div class="pt-4 first:pt-0 flex flex-col gap-2 text-xs">
                                <div class="flex justify-between items-start gap-3">
                                    <span class="text-white font-bold line-clamp-1">{{ $item['name'] }}</span>
                                    <span class="text-emerald-400 font-extrabold flex-shrink-0">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-[10px] text-slate-500">
                                    <span>Periode: {{ $item['total_days'] }} Hari</span>
                                    <span>{{ \Carbon\Carbon::parse($item['start_date'])->format('d M') }} - {{ \Carbon\Carbon::parse($item['end_date'])->format('d M') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="border-slate-800">

                    <div class="space-y-3 text-xs text-slate-400">
                        <div class="flex justify-between">
                            <span>Subtotal Sewa:</span>
                            <span class="text-white">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkir / Biaya Layanan:</span>
                            <span class="text-emerald-400 font-bold font-mono">FREE</span>
                        </div>
                        <hr class="border-slate-800 my-2">
                        <div class="flex justify-between text-sm font-bold text-white">
                            <span class="text-emerald-400">Grand Total:</span>
                            <span class="text-emerald-400 font-extrabold text-base">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl font-bold bg-emerald-500 text-slate-950 hover:bg-emerald-400 hover-glow-emerald transition-all text-center flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10">
                        <i data-lucide="lock" class="h-4.5 w-4.5"></i> Konfirmasi Booking
                    </button>
                </div>
            </div>

        </form>
    </div>
</section>
@endsection
