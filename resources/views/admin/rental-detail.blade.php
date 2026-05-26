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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-slate-300">
                <div class="space-y-2">
                    <p class="text-slate-500 font-bold uppercase text-[9px] tracking-wide">Rincian Customer</p>
                    <p class="font-bold text-white text-sm">{{ $rental->user->name }}</p>
                    <p class="font-mono text-slate-400">{{ $rental->user->email }}</p>
                    <p class="font-mono text-slate-400">WA Hotline: {{ $rental->phone }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-slate-500 font-bold uppercase text-[9px] tracking-wide">Alamat Pengiriman / Ambil</p>
                    <p class="font-semibold text-white leading-relaxed">{{ $rental->shipping_address }}</p>
                </div>
            </div>

            @if($rental->note)
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-900 text-xs">
                    <p class="text-[9px] text-slate-500 uppercase font-bold tracking-wider mb-1">Catatan Tambahan Customer</p>
                    <p class="text-slate-300 italic font-mono">"{{ $rental->note }}"</p>
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
            <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass space-y-6">
                <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-3 flex items-center gap-2">
                    <i data-lucide="image" class="text-slate-400 h-4.5 w-4.5"></i> Bukti Transfer Bank
                </h3>

                @if($rental->payment->payment_proof)
                    <div class="space-y-4">
                        <!-- Custom Receipt Preview Frame -->
                        <div class="rounded-xl border border-slate-850 overflow-hidden bg-slate-950 aspect-[3/4] flex flex-col justify-between p-4 relative text-center">
                            
                            <div class="flex justify-between items-center text-[10px] text-slate-500 border-b border-slate-900 pb-2">
                                <span class="font-bold">RECEIPT FILE</span>
                                <span>#PROOF-{{ $rental->id }}</span>
                            </div>

                            <div class="my-auto py-8">
                                <i data-lucide="file-check" class="h-12 w-12 text-emerald-400 mx-auto mb-3"></i>
                                <p class="text-xs font-bold text-white">Bukti Transfer Diunggah</p>
                                <p class="text-[10px] text-slate-500 mt-1 font-mono">File: {{ basename($rental->payment->payment_proof) }}</p>
                            </div>

                            <a href="{{ asset($rental->payment->payment_proof) }}" target="_blank" 
                               class="w-full py-2.5 rounded-lg bg-slate-900 hover:bg-slate-850 text-[10px] font-bold text-emerald-400 border border-slate-800 hover:border-emerald-500 transition-all flex items-center justify-center gap-1.5">
                                <i data-lucide="eye" class="h-3.5 w-3.5"></i> Buka Gambar Asli
                            </a>
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
