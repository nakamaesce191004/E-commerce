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
                    <p class="text-slate-500 font-bold uppercase text-[9px] tracking-wide">Rincian Customer</p>
                    <p class="font-bold text-white text-sm">{{ $rental->user->name }}</p>
                    <p class="font-mono text-slate-400">{{ $rental->user->email }}</p>
                    <p class="font-mono text-slate-400">WA Hotline: {{ $rental->phone }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-slate-500 font-bold uppercase text-[9px] tracking-wide">Alamat Domisili / Ambil</p>
                    <p class="font-semibold text-white leading-relaxed">{{ $rental->shipping_address }}</p>
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

    <!-- 2. Right Column: Operational cockpit controls -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Operational Cockpit Forms -->
        <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass space-y-6">
            <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-3 flex items-center gap-2">
                <i data-lucide="sliders" class="text-emerald-400 h-4.5 w-4.5"></i> Cockpit Kontrol Status
            </h3>

            <!-- We point the update to storeBooking or a specific update route. Since there is confirmationsUpdate, let's look at how we can update booking status.
                 Actually, we can use the same confirmation update or standard rental update if it existed.
                 Wait, we added a route 'admin.rentals.update' earlier but it was not present. Oh! In AdminDashboardController there is:
                 'rentalsUpdateStatus(Request $request, $id)' which points to 'admin.rentals.update'! Wait!
                 Is 'admin.rentals.update' defined in routes?
                 Yes! Let's check web.php:
                 'Route::put('/confirmations/{id}/update', [AdminDashboardController::class, 'confirmationsUpdate'])->name('confirmations.update');'
                 Ah! The route name is 'admin.confirmations.update'!
                 So we can submit to `route('admin.confirmations.update', $rental->id)` which is the active status update controller method!
                 Wait, confirmationsUpdate expects status 'approved' or 'rejected'.
                 Let's check if there is another status update method in AdminDashboardController.
                 Yes! Let's look at `rentalsUpdateStatus` in AdminDashboardController:
                 ```php
                 public function rentalsUpdateStatus(Request $request, $id) {
                     $request->validate([
                         'status' => 'required|in:pending,approved,borrowed,completed,rejected',
                         'payment_status' => 'required|in:unpaid,paid,expired'
                     ]);
                     ...
                 }
                 ```
                 But wait! Is the route for `rentalsUpdateStatus` defined in routes/web.php?
                 No, it was removed or commented out!
                 Let's define the route `admin.bookings.update` pointing to `AdminDashboardController@rentalsUpdateStatus` (or similar) so we can update to all statuses: approved, borrowed, completed, rejected!
                 Yes, this is extremely helpful and necessary! Let's add that route in web.php.
            -->
            <form action="{{ route('admin.bookings.update', $rental->id) }}" method="POST" class="space-y-5 text-xs">
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
