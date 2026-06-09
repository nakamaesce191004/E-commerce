@extends('layouts.frontend')

@section('title', 'Detail Transaksi Peminjaman #' . ($rental->payment->transaction_id ?? $rental->id))

@section('content')
<section class="py-12 bg-slate-950 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex text-xs text-slate-500 gap-2 mb-8 items-center">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <i data-lucide="chevron-right" class="h-3 w-3"></i>
            <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
            <i data-lucide="chevron-right" class="h-3 w-3"></i>
            <span class="text-slate-300 font-semibold truncate">Detail Transaksi #{{ $rental->id }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Details & Items List (Left) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Order Timeline Tracker -->
                <div class="p-6 rounded-2xl bg-slate-900/30 border border-slate-900 glass">
                    <h3 class="font-display font-bold text-white text-sm mb-6 flex items-center gap-2">
                        <i data-lucide="git-commit" class="text-slate-400 h-4.5 w-4.5"></i> Status Perjalanan Rental
                    </h3>
                    
                    <!-- Steps Timeline -->
                    <div class="grid grid-cols-5 gap-3 text-center text-[10px]">
                        <!-- Step 1 -->
                        <div class="space-y-2">
                            <div class="h-8 w-8 rounded-full bg-emerald-500 text-slate-950 font-bold flex items-center justify-center mx-auto ring-4 ring-emerald-500/10">
                                <i data-lucide="file-text" class="h-4 w-4"></i>
                            </div>
                            <p class="font-semibold text-white">Pending</p>
                            <p class="text-[9px] text-slate-500">Order Dibuat</p>
                        </div>

                        <!-- Step 2 -->
                        <div class="space-y-2">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center mx-auto transition-all
                                @if(in_array($rental->status, ['approved', 'borrowed', 'completed']))
                                    bg-emerald-500 text-slate-950 ring-4 ring-emerald-500/10
                                @else
                                    bg-slate-900 text-slate-500 border border-slate-800
                                @endif">
                                <i data-lucide="shield-check" class="h-4 w-4"></i>
                            </div>
                            <p class="font-semibold @if(in_array($rental->status, ['approved', 'borrowed', 'completed'])) text-white @else text-slate-500 @endif">Disetujui</p>
                            <p class="text-[9px] text-slate-500">Diverifikasi Admin</p>
                        </div>

                        <!-- Step 3 -->
                        <div class="space-y-2">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center mx-auto transition-all
                                @if(in_array($rental->status, ['borrowed', 'completed']))
                                    bg-emerald-500 text-slate-950 ring-4 ring-emerald-500/10
                                @else
                                    bg-slate-900 text-slate-500 border border-slate-800
                                @endif">
                                <i data-lucide="truck" class="h-4 w-4"></i>
                            </div>
                            <p class="font-semibold @if(in_array($rental->status, ['borrowed', 'completed'])) text-white @else text-slate-500 @endif">Dipinjam</p>
                            <p class="text-[9px] text-slate-500">Barang Digunakan</p>
                        </div>

                        <!-- Step 4 -->
                        <div class="space-y-2">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center mx-auto transition-all
                                @if($rental->status === 'completed')
                                    bg-emerald-500 text-slate-950 ring-4 ring-emerald-500/10
                                @else
                                    bg-slate-900 text-slate-500 border border-slate-800
                                @endif">
                                <i data-lucide="archive" class="h-4 w-4"></i>
                            </div>
                            <p class="font-semibold @if($rental->status === 'completed') text-white @else text-slate-500 @endif">Selesai</p>
                            <p class="text-[9px] text-slate-500">Barang Kembali</p>
                        </div>

                        <!-- Step 5: Rejected Status (Only shown if status rejected) -->
                        <div class="space-y-2">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center mx-auto transition-all
                                @if($rental->status === 'rejected')
                                    bg-red-500 text-slate-950 ring-4 ring-red-500/10
                                @else
                                    bg-slate-900 text-slate-500 border border-slate-800
                                @endif">
                                <i data-lucide="x" class="h-4 w-4"></i>
                            </div>
                            <p class="font-semibold @if($rental->status === 'rejected') text-red-400 @else text-slate-500 @endif">Ditolak</p>
                            <p class="text-[9px] text-slate-500">Order Dibatalkan</p>
                        </div>
                    </div>

                </div>

                <!-- Products hired list -->
                <div class="p-6 rounded-2xl bg-slate-900/30 border border-slate-900 glass space-y-6">
                    <h3 class="font-display font-bold text-white text-sm border-b border-slate-800 pb-3 flex items-center gap-2">
                        <i data-lucide="package" class="text-slate-400 h-4.5 w-4.5"></i> Daftar Barang Sewa
                    </h3>

                    <div class="divide-y divide-slate-900 space-y-6">
                        @foreach($rental->items as $item)
                            <div class="pt-6 first:pt-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-16 bg-slate-950 border border-slate-900 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="{{ $item->product->category->type === 'camera' ? 'camera' : 'tent' }}" class="h-5 w-5 text-slate-700"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-display font-bold text-white text-sm hover:text-emerald-400 transition-colors">
                                            <a href="{{ route('catalog.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                        </h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Rp {{ number_format($item->price_per_day, 0, ',', '.') }} / hari</p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Subtotal</p>
                                    <p class="text-sm font-extrabold text-emerald-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <!-- Rating & Review Form (If Rental status is Completed) -->
                            @if($rental->status === 'completed')
                                @php
                                    $hasReviewed = $item->product->reviews()->where('user_id', auth()->id())->first();
                                @endphp
                                <div class="mt-4 p-4 rounded-xl bg-slate-950 border border-slate-900">
                                    @if($hasReviewed)
                                        <div class="space-y-1.5">
                                            <p class="text-[10px] text-slate-500 font-bold uppercase">Ulasan Anda</p>
                                            <div class="flex text-amber-500 gap-0.5 mb-1.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i data-lucide="star" class="h-3 w-3 {{ $i <= $hasReviewed->rating ? 'fill-amber-500 text-amber-500' : 'text-slate-800' }}"></i>
                                                @endfor
                                            </div>
                                            <p class="text-xs text-slate-300 italic">"{{ $hasReviewed->comment }}"</p>
                                        </div>
                                    @else
                                        <!-- Review Form input -->
                                        <form action="{{ route('dashboard.review') }}" method="POST" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                            <div class="flex items-center gap-3">
                                                <label class="text-[10px] font-bold text-slate-400 uppercase">Beri Bintang:</label>
                                                <select name="rating" required class="bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-lg text-xs text-white px-2 py-1 focus:outline-none">
                                                    <option value="5">5 - Sangat Puas</option>
                                                    <option value="4">4 - Puas</option>
                                                    <option value="3">3 - Biasa Saja</option>
                                                    <option value="2">2 - Kecewa</option>
                                                    <option value="1">1 - Sangat Kecewa</option>
                                                </select>
                                            </div>
                                            <div>
                                                <textarea name="comment" required rows="2" placeholder="Tulis komentar ulasan Anda mengenai kondisi barang..." 
                                                          class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none resize-none"></textarea>
                                            </div>
                                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-500 hover:bg-emerald-400 text-slate-950 transition-all flex items-center gap-1 shadow-md">
                                                <i data-lucide="send" class="h-3 w-3"></i> Kirim Ulasan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Billing Details, Upload proof & WhatsApp Confirm (Right) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Order Summary & Shipping Address -->
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 glass space-y-6">
                    <h3 class="font-display font-bold text-white text-base border-b border-slate-800 pb-3">Ringkasan Invoice</h3>
                    
                    <div class="space-y-4 text-xs text-slate-300">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Invoice No:</span>
                            <span class="text-white font-mono font-bold">#{{ $rental->payment->transaction_id ?? $rental->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Metode Bayar:</span>
                            <span class="text-white uppercase font-bold">{{ $rental->payment_method }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Periode Sewa:</span>
                            <span class="text-white font-bold">{{ $rental->start_date->format('d M Y') }} - {{ $rental->end_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Durasi:</span>
                            <span class="text-white font-bold">{{ $rental->total_days }} Hari</span>
                        </div>
                        <div class="space-y-1">
                            <p class="text-slate-500">Pengambilan / Domisili:</p>
                            <p class="text-white leading-relaxed font-semibold">{{ $rental->shipping_address }}</p>
                        </div>
                        <div class="space-y-1 bg-slate-950 p-3 rounded-xl border border-slate-900">
                            <p class="text-slate-500 font-bold uppercase text-[9px]">Identitas Pengambil:</p>
                            <p class="text-white font-semibold">{{ $rental->ktp_name ?? '-' }}</p>
                            <p class="text-slate-400 font-mono text-[11px]">NIK: {{ $rental->nik ?? '-' }}</p>
                            @if($rental->ktp_photo)
                                <a href="{{ asset($rental->ktp_photo) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-emerald-400 hover:underline">
                                    <i data-lucide="external-link" class="h-3 w-3"></i> Lihat Foto KTP
                                </a>
                            @endif
                        </div>
                        @if($rental->note)
                            <div class="space-y-1 bg-slate-950 p-3 rounded-xl border border-slate-900">
                                <p class="text-slate-500 font-bold uppercase text-[9px]">Catatan Anda:</p>
                                <p class="text-slate-400 italic text-[11px]">"{{ $rental->note }}"</p>
                            </div>
                        @endif
                        @if($rental->admin_note)
                            <div class="space-y-1 bg-emerald-950/20 p-3 rounded-xl border border-emerald-900/20 mt-2">
                                <p class="text-emerald-400 font-bold uppercase text-[9px]">Catatan Verifikasi Admin:</p>
                                <p class="text-slate-300 text-[11px] leading-relaxed">"{{ $rental->admin_note }}"</p>
                            </div>
                        @endif
                        <hr class="border-slate-800">
                        <div class="flex justify-between text-sm font-bold text-white">
                            <span class="text-emerald-400">Total Tagihan:</span>
                            <span class="text-emerald-400 font-extrabold text-base">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Section: Transfer / Midtrans Actions -->
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 glass space-y-6">
                    <h3 class="font-display font-bold text-white text-base border-b border-slate-800 pb-3">Status Pembayaran</h3>

                    @if($rental->payment_status === 'paid')
                        <!-- Lunass -->
                        <div class="p-4 rounded-2xl bg-emerald-950/20 border border-emerald-900/20 text-center space-y-3">
                            <i data-lucide="check-circle" class="h-10 w-10 text-emerald-400 mx-auto"></i>
                            <h4 class="font-bold text-white text-sm">Pembayaran Lunas (Paid)</h4>
                            <p class="text-[10px] text-slate-400 leading-relaxed">
                                Transaksi Anda telah lunas dan terverifikasi di sistem kami. Peralatan Anda aman dan siap diserahkan sesuai jadwal!
                            </p>
                        </div>
                    @elseif($rental->payment_status === 'expired')
                        <!-- Expired -->
                        <div class="p-4 rounded-2xl bg-red-950/20 border border-red-900/20 text-center space-y-3">
                            <i data-lucide="alert-triangle" class="h-10 w-10 text-red-500 mx-auto"></i>
                            <h4 class="font-bold text-white text-sm">Pembayaran Expired / Gagal</h4>
                            <p class="text-[10px] text-slate-400 leading-relaxed">
                                Transaksi ini telah kedaluwarsa atau dibatalkan karena tidak ada pembayaran yang diterima dalam batas waktu. Silakan ajukan booking baru.
                            </p>
                        </div>
                    @else
                        <!-- UNPAID: SHOW ACTIONS BASED ON METHOD -->
                        @if($rental->payment_method === 'midtrans')
                            <!-- Midtrans action button -->
                            <div class="space-y-4">
                                <p class="text-xs text-slate-400 leading-relaxed">Penyewaan ini dibayar menggunakan sistem gerbang online Midtrans. Klik tombol di bawah untuk membuka simulator pembayaran Snap.</p>
                                <a href="{{ route('checkout.payment', $rental->id) }}" class="w-full py-3.5 rounded-xl font-bold bg-emerald-500 text-slate-950 hover:bg-emerald-400 hover-glow-emerald transition-all text-xs flex items-center justify-center gap-1.5 shadow-lg shadow-emerald-500/10">
                                    <i data-lucide="credit-card" class="h-4 w-4"></i> Buka Portal Pembayaran
                                </a>
                            </div>
                        @else
                            <!-- Bank Transfer Proof Upload Form -->
                            <div class="space-y-6">
                                <div class="bg-slate-950 p-4 rounded-xl border border-slate-900 space-y-3">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rekening Tujuan Transfer</p>
                                    
                                    <div class="space-y-2 text-[11px] text-slate-300">
                                        <div class="border-b border-slate-900 pb-1.5">
                                            <span class="font-bold text-emerald-400">BCA:</span> 123-456-7890 <br>
                                            <span class="text-[9px] text-slate-500">a/n PT EquipRent Outdoor Indonesia</span>
                                        </div>
                                        <div>
                                            <span class="font-bold text-emerald-400">MANDIRI:</span> 098-765-4321 <br>
                                            <span class="text-[9px] text-slate-500">a/n PT EquipRent Outdoor Indonesia</span>
                                        </div>
                                    </div>
                                </div>

                                @if($rental->payment->payment_proof)
                                    <!-- Proof already uploaded, waiting -->
                                    <div class="p-4 rounded-xl bg-slate-950 border border-slate-900 text-center space-y-2">
                                        <i data-lucide="refresh-cw" class="h-8 w-8 text-yellow-500 mx-auto animate-spin"></i>
                                        <h4 class="font-bold text-white text-xs">Menunggu Verifikasi Admin</h4>
                                        <p class="text-[10px] text-slate-500">Anda telah mengunggah bukti transfer. Admin kami sedang meneliti pembayaran Anda. Mohon ditunggu.</p>
                                        <div class="pt-2">
                                            <a href="{{ asset($rental->payment->payment_proof) }}" target="_blank" class="text-[10px] text-emerald-400 hover:underline">Lihat Bukti yang Diupload &rarr;</a>
                                        </div>
                                    </div>
                                @else
                                    <!-- Upload proof Form -->
                                    <form action="{{ route('dashboard.upload-proof', $rental->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="payment_proof" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Unggah Bukti Transfer</label>
                                            <input type="file" id="payment_proof" name="payment_proof" required class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-xl p-2.5 focus:border-emerald-500 focus:outline-none">
                                            <p class="text-[9px] text-slate-500 mt-1.5">Mendukung format gambar JPEG, JPG, atau PNG. Maksimal ukuran berkas 2MB.</p>
                                        </div>
                                        <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-emerald-400 font-bold border border-slate-800 hover:border-emerald-500 rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 shadow-md">
                                            <i data-lucide="upload" class="h-4 w-4"></i> Unggah Bukti Bayar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>

                <!-- WhatsApp Confirmation API Integration (waLink) -->
                @if($rental->payment_status === 'unpaid' && !$rental->payment->payment_proof)
                    <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 glass space-y-4">
                        <h4 class="font-display font-bold text-white text-sm flex items-center gap-1.5">
                            <i data-lucide="message-square" class="text-emerald-400 h-4.5 w-4.5"></i> Konfirmasi Cepat WA
                        </h4>
                        <p class="text-[10px] text-slate-400 leading-relaxed">
                            Ingin mempercepat proses verifikasi atau bayar di showroom? Klik tombol di bawah untuk langsung mengirimkan invoice details terformat secara instan ke WhatsApp admin!
                        </p>
                        
                        <a href="{{ $waLink }}" target="_blank" class="w-full py-3.5 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition-all text-xs flex items-center justify-center gap-2 shadow-md">
                            <!-- SVG WhatsApp logo icon -->
                            <svg class="h-4.5 w-4.5 fill-current" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.022-.08-.124-.22-.326-.32-.2-.1-.98-.488-1.132-.544-.152-.056-.263-.082-.375.082-.112.164-.432.544-.53.656-.097.112-.196.126-.396.026a5.556 5.556 0 0 1-2.224-1.372 4.908 4.908 0 0 1-1.25-1.564c-.113-.19-.012-.294.088-.393.09-.09.2-.23.3-.35.1-.116.13-.198.196-.33.067-.13.033-.244-.017-.345-.05-.1-.412-1-.564-1.375-.148-.36-.3-.31-.41-.31h-.35a.696.696 0 0 0-.5.226c-.173.19-.66.645-.66 1.572s.675 1.82 1.77 1.966c.11.014 2.122 3.24 5.137 4.542.717.31 1.277.494 1.713.633.72.228 1.374.197 1.892.12.577-.085 1.772-.724 2.022-1.424.25-.7.25-1.3.176-1.424-.07-.116-.176-.165-.276-.215zM12.004 2c-5.522 0-10 4.477-10 10 0 1.954.563 3.775 1.536 5.318L2 22l4.896-1.284A9.957 9.957 0 0 0 12.004 22c5.522 0 10-4.477 10-10s-4.478-10-10-10z"></path>
                            </svg>
                            Kirim Invoice via WhatsApp
                        </a>
                    </div>
                @endif

            </div>

        </div>
    </div>
</section>
@endsection
