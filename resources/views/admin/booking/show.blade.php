@extends('layouts.admin')

@section('page_title', 'Detail Booking #' . ($rental->payment->transaction_id ?? $rental->id))

@section('content')
<!-- Breadcrumbs -->
<nav class="flex text-xs text-slate-500 gap-2 mb-8 items-center">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Admin Dashboard</a>
    <i data-lucide="chevron-right" class="h-3 w-3"></i>
    <a href="{{ route('admin.bookings.index') }}" class="hover:text-white transition-colors">Booking Alat</a>
    <i data-lucide="chevron-right" class="h-3 w-3"></i>
    <span class="text-slate-300 font-semibold truncate">Detail Booking #{{ $rental->id }}</span>
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
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-slate-300">
                <div class="space-y-2">
                    <!-- <p class="text-slate-500 font-bold uppercase text-[9px] tracking-wide">Rincian Customer</p> -->
                    <p class="font-bold text-white text-sm">{{ $rental->user->name }}</p>
                    <p class="font-mono text-slate-400">{{ $rental->user->email }}</p>
                    <p class="font-mono text-slate-400">WA Hotline: {{ $rental->phone }}</p>
                </div>
                <div class="space-y-2">
                    <p class="font-semibold text-white leading-relaxed">{{ $rental->shipping_address }}</p>
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

            @if($rental->note)
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-900 text-xs">
                    <p class="text-[9px] text-slate-500 uppercase font-bold tracking-wider mb-1">Catatan Tambahan Customer</p>
                    <p class="text-slate-300 italic font-mono">"{{ $rental->note }}"</p>
                </div>
            @endif

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
                                <p class="font-semibold text-slate-300">{{ $rental->total_days }} H (24 jam)</p>
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
                <span class="text-white font-bold">{{ $rental->start_at ? $rental->start_at->format('d M Y H:i') : $rental->start_date->format('d M Y') }} s/d {{ $rental->end_at ? $rental->end_at->format('d M Y H:i') : $rental->end_date->format('d M Y') }} ({{ $rental->total_days }} Hari)</span>
            </div>
        </div>

    </div>

    <!-- 2. Right Column: Operational cockpit controls -->
    <div class="lg:col-span-4 space-y-6" x-data="{ zoomModal: false }">
        <!-- Booking Status Summary -->
        <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass space-y-5">
            <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-3 flex items-center gap-2">
                <i data-lucide="clipboard-check" class="text-emerald-400 h-4.5 w-4.5"></i> Ringkasan Konfirmasi
            </h3>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-900 space-y-2">
                    <p class="text-[9px] text-slate-500 uppercase font-bold tracking-wider">Status Booking</p>
                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                        @if($rental->status === 'pending') bg-yellow-950 text-yellow-400 border border-yellow-500/20
                        @elseif($rental->status === 'approved') bg-emerald-950 text-emerald-400 border border-emerald-500/20
                        @elseif($rental->status === 'borrowed') bg-blue-950 text-blue-400 border border-blue-500/20
                        @elseif($rental->status === 'completed') bg-emerald-500/10 text-emerald-300 border border-emerald-500/20
                        @else bg-red-950 text-red-400 border border-red-500/20 @endif">
                        @if($rental->status === 'pending') Menunggu
                        @elseif($rental->status === 'approved') Disetujui
                        @elseif($rental->status === 'borrowed') Dipinjam
                        @elseif($rental->status === 'completed') Selesai
                        @else Ditolak @endif
                    </span>
                </div>

                <div class="p-4 rounded-xl bg-slate-950 border border-slate-900 space-y-2">
                    <p class="text-[9px] text-slate-500 uppercase font-bold tracking-wider">Pembayaran</p>
                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                        @if($rental->payment_status === 'paid') bg-emerald-950 text-emerald-400 border border-emerald-500/20
                        @elseif($rental->payment_status === 'unpaid') bg-yellow-950 text-yellow-400 border border-yellow-500/20
                        @else bg-red-950 text-red-400 border border-red-500/20 @endif">
                        {{ $rental->payment_status === 'paid' ? 'Lunas' : ($rental->payment_status === 'unpaid' ? 'Belum Bayar' : 'Expired') }}
                    </span>
                </div>
            </div>

            @if($rental->payment_method === 'transfer')
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-900 text-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-[9px] text-slate-500 uppercase font-bold tracking-wider">Bukti Transfer</p>
                        <span class="text-[10px] text-slate-500 uppercase">{{ $rental->payment_method }}</span>
                    </div>

                    @if($rental->payment && $rental->payment->payment_proof)
                        <button type="button" @click="zoomModal = true" class="block w-full rounded-lg border border-slate-800 overflow-hidden bg-slate-900 group">
                            <img src="{{ asset($rental->payment->payment_proof) }}" alt="Bukti transfer booking #{{ $rental->id }}" class="h-40 w-full object-contain transition-transform duration-300 group-hover:scale-105">
                        </button>
                        <a href="{{ asset($rental->payment->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-emerald-400 hover:underline">
                            <i data-lucide="external-link" class="h-3 w-3"></i> Buka Bukti Transfer
                        </a>
                    @else
                        <p class="text-slate-500">Belum ada bukti transfer.</p>
                    @endif
                </div>
            @endif
        </div>

        @if($rental->payment && $rental->payment->payment_proof)
            <div x-show="zoomModal"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/90 p-4 sm:p-6 md:p-10"
                 x-transition
                 style="display: none;"
                 @keydown.escape.window="zoomModal = false">
                <div class="absolute inset-0 cursor-zoom-out" @click="zoomModal = false"></div>
                <div class="relative bg-slate-900 rounded-2xl border border-slate-800 shadow-2xl max-w-3xl w-full max-h-[85vh] flex flex-col z-10 overflow-hidden" @click.away="zoomModal = false">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
                        <h4 class="font-bold text-xs text-white">Bukti Pembayaran #{{ $rental->payment->transaction_id ?? $rental->id }}</h4>
                        <button type="button" @click="zoomModal = false" class="text-slate-400 hover:text-white transition-colors">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>
                    <div class="flex-grow overflow-auto p-4 flex items-center justify-center bg-slate-950">
                        <img src="{{ asset($rental->payment->payment_proof) }}" alt="Bukti Pembayaran Full" class="max-w-full max-h-[60vh] object-contain rounded-lg">
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Operational Cockpit Forms -->
        <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass space-y-6">
            <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-3 flex items-center gap-2">
                <i data-lucide="sliders" class="text-emerald-400 h-4.5 w-4.5"></i> Cockpit Kontrol Status
            </h3>

            <form action="{{ route('admin.bookings.update', $rental->id) }}" method="POST" class="space-y-5 text-xs" onsubmit="return confirm('Simpan perubahan status booking ini?');">
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
                    <textarea id="admin_note" name="admin_note" rows="3" placeholder="Masukkan catatan atau memo..." class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none placeholder-slate-600 leading-relaxed">{{ old('admin_note', $rental->admin_note) }}</textarea>
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

    </div>

</div>
@endsection
