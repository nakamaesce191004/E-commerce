@extends('layouts.admin')

@section('page_title', 'Kelola Transaksi #' . ($rental->payment->transaction_id ?? $rental->id))

@section('content')
<!-- Breadcrumbs -->
<nav class="flex text-xs text-slate-500 gap-2 mb-8 items-center">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Admin Dashboard</a>
    <i data-lucide="chevron-right" class="h-3 w-3"></i>
    <a href="{{ route('admin.rentals.index') }}" class="hover:text-white transition-colors">Transaksi Sewa</a>
    <i data-lucide="chevron-right" class="h-3 w-3"></i>
    <span class="text-slate-300 font-semibold truncate">Detail Transaksi #{{ $rental->id }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
    
    <!-- 1. Left Column: Details & Items -->
    <div class="lg:col-span-8 space-y-6">
        
        <!-- Transaction Detail Summary -->
        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 glass space-y-6">
            <div class="flex items-center justify-between border-b border-slate-850 pb-3">
                <h3 class="font-display font-bold text-white text-sm flex items-center gap-2">
                    <i data-lucide="file-text" class="text-slate-400 h-4.5 w-4.5"></i> Informasi Invoice & Renter
                </h3>
                
                <!-- Print Invoice Button -->
                <a href="{{ route('admin.rentals.invoice', $rental->id) }}" target="_blank" class="px-3.5 py-1.5 rounded-lg border border-slate-800 hover:border-emerald-500 hover:bg-slate-950 text-slate-400 hover:text-emerald-400 text-xs font-bold transition-all flex items-center gap-1.5">
                    <i data-lucide="printer" class="h-3.5 w-3.5"></i> Cetak Invoice PDF
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 text-xs text-slate-300">
                <div class="space-y-2">
                    <p class="text-slate-500 font-bold uppercase text-[9px] tracking-wide">Rincian Customer</p>
                    <p class="font-bold text-white text-sm">{{ $rental->user->name }}</p>
                    <p class="font-mono text-slate-400">{{ $rental->user->email }}</p>
                    <p class="font-mono text-slate-400">WA Hotline: {{ $rental->phone }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-900 space-y-2">
                    <p class="text-[9px] text-slate-500 uppercase font-bold tracking-wider">Identitas Pengambil</p>
                    <p class="font-bold text-white">{{ $rental->ktp_name ?? '-' }}</p>
                    <p class="font-mono text-slate-400">NIK: {{ $rental->nik ?? '-' }}</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-900 space-y-3">
                    <p class="text-[9px] text-slate-500 uppercase font-bold tracking-wider">Foto KTP</p>
                    @if($rental->ktp_photo)
                        <a href="{{ asset($rental->ktp_photo) }}" target="_blank" class="block rounded-lg border border-slate-800 overflow-hidden bg-slate-900 group">
                            <img src="{{ asset($rental->ktp_photo) }}" alt="Foto KTP {{ $rental->ktp_name }}" class="h-36 w-full object-contain transition-transform duration-300 group-hover:scale-105">
                        </a>
                        <a href="{{ asset($rental->ktp_photo) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-emerald-400 hover:underline">
                            <i data-lucide="external-link" class="h-3 w-3"></i> Buka Foto KTP
                        </a>
                    @else
                        <p class="text-slate-500">Belum ada foto KTP.</p>
                    @endif
                </div>
            </div>

            @if($rental->admin_note)
                <div class="p-4 rounded-xl bg-emerald-950/10 border border-emerald-900/30 text-xs mt-3">
                    <p class="text-[9px] text-emerald-400 uppercase font-bold tracking-wider mb-1">Catatan Verifikasi Admin</p>
                    <p class="text-slate-300 font-mono">"{{ $rental->admin_note }}"</p>
                </div>
            @endif
        </div>

        <!-- Hired Items list -->
        <div class="p-6 rounded-2xl bg-slate-900/30 border border-slate-900 glass space-y-6">
            <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-3 flex items-center gap-2">
                <i data-lucide="package" class="text-slate-400 h-4.5 w-4.5"></i> Unit Alat yang Disewa
            </h3>

            <div class="divide-y divide-slate-850 space-y-6">
                @foreach($rental->items as $item)
                    <div class="pt-6 first:pt-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-12 rounded bg-slate-950 border border-slate-850 flex items-center justify-center text-slate-600 flex-shrink-0">
                                <i data-lucide="{{ $item->product->category->type === 'camera' ? 'camera' : 'tent' }}" class="h-4.5 w-4.5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-200 hover:text-emerald-400 transition-colors">
                                    <a href="{{ route('catalog.show', $item->product->slug) }}" target="_blank">{{ $item->product->name }}</a>
                                </h4>
                                <p class="text-[10px] text-slate-500 mt-0.5 uppercase tracking-wide">{{ $item->product->category->name }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-6 text-center sm:text-right">
                            <div>
                                <p class="text-[9px] text-slate-500 uppercase">Tarif</p>
                                <p class="font-semibold text-slate-300">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-500 uppercase">Durasi</p>
                                <p class="font-semibold text-slate-300">{{ $rental->total_days }} H</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] text-slate-500 uppercase">Subtotal</p>
                                <p class="font-extrabold text-emerald-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="border-t border-slate-850 pt-4 flex justify-between items-center text-xs">
                <span class="text-slate-400">Total Periode Sewa:</span>
                <span class="text-white font-bold">{{ $rental->start_date->format('d M Y') }} s/d {{ $rental->end_date->format('d M Y') }} ({{ $rental->total_days }} Hari)</span>
            </div>
        </div>

    </div>

    <!-- 2. Right Column: Payment receipt & Operational cockpit controls -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Operational Cockpit Forms -->
        <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass space-y-6">
            <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-3 flex items-center gap-2">
                <i data-lucide="sliders" class="text-emerald-400 h-4.5 w-4.5"></i> Cockpit Kontrol Status
            </h3>

            <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST" class="space-y-5 text-xs">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="status" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Status Peminjaman</label>
                    <select id="status" name="status" required class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                        <option value="pending" {{ $rental->status === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="approved" {{ $rental->status === 'approved' ? 'selected' : '' }}>Disetujui (Siap Kirim/Ambil)</option>
                        <option value="borrowed" {{ $rental->status === 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam (Borrowed)</option>
                        <option value="completed" {{ $rental->status === 'completed' ? 'selected' : '' }}>Telah Kembali (Selesai)</option>
                        <option value="rejected" {{ $rental->status === 'rejected' ? 'selected' : '' }}>Dibatalkan / Ditolak (Rejected)</option>
                    </select>
                </div>

                <div>
                    <label for="payment_status" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Status Pembayaran</label>
                    <select id="payment_status" name="payment_status" required class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                        <option value="unpaid" {{ $rental->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Bayar (Unpaid)</option>
                        <option value="paid" {{ $rental->payment_status === 'paid' ? 'selected' : '' }}>Lunas Terverifikasi (Paid)</option>
                        <option value="expired" {{ $rental->payment_status === 'expired' ? 'selected' : '' }}>Kedaluwarsa / Batalkan (Expired)</option>
                    </select>
                </div>

                <div>
                    <label for="admin_note" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Catatan Verifikasi Admin (Opsional)</label>
                    <textarea id="admin_note" name="admin_note" rows="3" placeholder="Contoh: Bukti bayar terverifikasi Rp 500k via Mandiri, atau alasan penolakan..." class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none placeholder-slate-600 leading-relaxed">{{ old('admin_note', $rental->admin_note) }}</textarea>
                </div>

                <div class="bg-slate-950 p-4 rounded-xl border border-slate-950 space-y-2">
                    <div class="flex justify-between font-bold text-slate-400">
                        <span>Total Tagihan:</span>
                        <span class="text-emerald-400 text-sm">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold hover-glow-emerald rounded-xl transition-all shadow-md">
                    Perbarui Status Sewa
                </button>
            </form>
        </div>

        <!-- Receipt Proof Visualizer -->
        @if($rental->payment_method === 'transfer')
            <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass space-y-6" x-data="{ zoomModal: false }">
                <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-3 flex items-center gap-2">
                    <i data-lucide="image" class="text-slate-400 h-4.5 w-4.5"></i> Bukti Transfer Bank
                </h3>

                @if($rental->payment && $rental->payment->payment_proof)
                    <div class="space-y-4">
                        <!-- Custom Receipt Preview Frame -->
                        <div class="rounded-xl border border-slate-850 overflow-hidden bg-slate-950 p-2 relative text-center group cursor-pointer" @click="zoomModal = true">
                            
                            <div class="flex justify-between items-center text-[10px] text-slate-500 border-b border-slate-900 pb-2 mb-3 px-2">
                                <span class="font-bold">RECEIPT PREVIEW</span>
                                <span>#PROOF-{{ $rental->id }}</span>
                            </div>

                            <div class="relative overflow-hidden rounded-lg bg-slate-900 aspect-[3/4] flex items-center justify-center border border-slate-800/40">
                                <img src="{{ asset($rental->payment->payment_proof) }}" 
                                     alt="Bukti Transfer" 
                                     class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-3.5 py-1.5 rounded-lg bg-emerald-500 text-slate-950 font-bold text-xs flex items-center gap-1.5 shadow-lg shadow-emerald-500/20">
                                        <i data-lucide="maximize-2" class="h-3.5 w-3.5"></i> Perbesar Bukti
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3 text-left px-2">
                                <p class="text-[10px] text-slate-500 font-mono truncate">File: {{ basename($rental->payment->payment_proof) }}</p>
                            </div>
                        </div>

                        <!-- Lightbox Modal using AlpineJS -->
                        <div x-show="zoomModal" 
                             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/90 p-4 sm:p-6 md:p-10"
                             x-transition
                             style="display: none;"
                             @keydown.escape.window="zoomModal = false">
                            <div class="absolute inset-0 cursor-zoom-out" @click="zoomModal = false"></div>
                            
                            <div class="relative bg-slate-900 rounded-2xl border border-slate-800 shadow-2xl max-w-3xl w-full max-h-[85vh] flex flex-col z-10 overflow-hidden" 
                                 @click.away="zoomModal = false">
                                
                                <!-- Header -->
                                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
                                    <div class="flex items-center gap-2">
                                        <span class="p-1.5 rounded bg-emerald-950/40 text-emerald-400 border border-emerald-900/30">
                                            <i data-lucide="file-check" class="h-4 w-4"></i>
                                        </span>
                                        <h4 class="font-bold text-xs text-white">Bukti Pembayaran #{{ $rental->payment->transaction_id ?? $rental->id }}</h4>
                                    </div>
                                    <button type="button" @click="zoomModal = false" class="text-slate-400 hover:text-white transition-colors">
                                        <i data-lucide="x" class="h-5 w-5"></i>
                                    </button>
                                </div>

                                <!-- Body -->
                                <div class="flex-grow overflow-auto p-4 flex items-center justify-center bg-slate-950">
                                    <img src="{{ asset($rental->payment->payment_proof) }}" 
                                         alt="Bukti Pembayaran Full" 
                                         class="max-w-full max-h-[60vh] object-contain rounded-lg">
                                </div>

                                <!-- Footer Actions -->
                                <div class="flex items-center justify-between px-5 py-4 border-t border-slate-800 bg-slate-900/80 text-xs">
                                    <span class="text-slate-500 font-mono">{{ basename($rental->payment->payment_proof) }}</span>
                                    <a href="{{ asset($rental->payment->payment_proof) }}" target="_blank" 
                                       class="px-4 py-2 bg-slate-800 hover:bg-slate-750 text-white font-bold rounded-lg border border-slate-700 transition-colors flex items-center gap-1.5">
                                        <i data-lucide="download" class="h-3.5 w-3.5"></i> Unduh File Asli
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-slate-500 border border-dashed border-slate-800 rounded-xl space-y-2">
                        <i data-lucide="slash" class="h-8 w-8 mx-auto text-slate-700"></i>
                        <p class="text-xs">Customer belum mengunggah bukti pembayaran transfer.</p>
                    </div>
                @endif
            </div>
        @endif

    </div>

</div>
@endsection
