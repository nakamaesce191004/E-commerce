@extends('layouts.admin')

@section('page_title', 'Kelola Transaksi Rental')

@section('content')
<div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
    
    <!-- Header with Search & Filters -->
    <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-6 border-b border-slate-850 pb-6 mb-6">
        <div>
            <h2 class="font-display font-extrabold text-lg text-white">Daftar Transaksi Penyewaan</h2>
            <p class="text-xs text-slate-500 mt-1">Lacak status persetujuan, pembayaran, pengiriman, dan pengembalian unit alat.</p>
        </div>

        <form action="{{ route('admin.rentals.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full xl:w-auto text-xs">
            <!-- Search -->
            <div class="relative w-full sm:w-64">
                  <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, ID..." 
                      class="icon-input w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl pl-9 pr-4 py-2 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
                <i data-lucide="search" class="absolute left-3.5 top-2.5 h-3.5 w-3.5 text-slate-500"></i>
            </div>

            <!-- Status Filter -->
            <select name="status" class="bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status Sewa</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui / Bersiap</option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Telah Selesai</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Dibatalkan / Ditolak</option>
            </select>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.rentals.index') }}" class="px-4 py-2 text-center border border-slate-850 hover:bg-slate-950 text-slate-400 hover:text-white rounded-xl font-semibold transition-all">Clear</a>
                <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all shadow-md">Apply</button>
            </div>
        </form>
    </div>

    <!-- Rentals Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left border-collapse">
            <thead>
                <tr class="text-slate-500 uppercase font-semibold border-b border-slate-850 pb-3">
                    <th class="pb-3">Invoice / Ref</th>
                    <th class="pb-3">Pelanggan / Renter</th>
                    <th class="pb-3">Periode Sewa</th>
                    <th class="pb-3">Grand Total</th>
                    <th class="pb-3">Metode</th>
                    <th class="pb-3">Status Bayar</th>
                    <th class="pb-3">Status Sewa</th>
                    <th class="pb-3 text-right">Kelola</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-850">
                @forelse($rentals as $rent)
                    <tr class="hover:bg-slate-900/10 transition-colors">
                        <!-- Invoice Ref -->
                        <td class="py-4 font-mono font-bold text-white">#{{ $rent->payment->transaction_id ?? $rent->id }}</td>
                        
                        <!-- Renter details -->
                        <td class="py-4">
                            <p class="font-bold text-slate-200">{{ $rent->user->name }}</p>
                            <p class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $rent->user->email }} | {{ $rent->phone }}</p>
                        </td>

                        <!-- Dates -->
                        <td class="py-4">
                            <span class="font-semibold text-slate-300">{{ $rent->start_date->format('d M Y') }} - {{ $rent->end_date->format('d M Y') }}</span>
                            <span class="text-[10px] text-slate-500 block mt-0.5">Durasi: {{ $rent->total_days }} Hari (24 jam)</span>
                        </td>

                        <!-- Price -->
                        <td class="py-4 font-bold text-emerald-400">Rp {{ number_format($rent->total_price, 0, ',', '.') }}</td>

                        <!-- Method -->
                        <td class="py-4 uppercase font-semibold text-slate-400">{{ $rent->payment_method }}</td>

                        <!-- Payment Status -->
                        <td class="py-4">
                            <span class="px-2.5 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase
                                @if($rent->payment_status === 'paid') bg-emerald-950 text-emerald-400 border border-emerald-500/20
                                @elseif($rent->payment_status === 'unpaid') bg-yellow-950 text-yellow-400 border border-yellow-500/20
                                @else bg-slate-950 text-slate-500 border border-slate-850 @endif">
                                {{ $rent->payment_status === 'paid' ? 'Paid' : ($rent->payment_status === 'unpaid' ? 'Unpaid' : 'Expired') }}
                            </span>
                            @if($rent->payment && $rent->payment->payment_proof)
                                <div class="mt-1.5 flex items-center gap-1 text-[10px] text-emerald-400 font-bold">
                                    <i data-lucide="file-check" class="h-3.5 w-3.5 text-emerald-400"></i> Bukti Ada
                                </div>
                            @elseif($rent->payment_method === 'transfer')
                                <div class="mt-1.5 flex items-center gap-1 text-[10px] text-slate-500 font-semibold">
                                    <i data-lucide="file-warning" class="h-3.5 w-3.5 text-slate-600"></i> Belum Upload
                                </div>
                            @endif
                        </td>

                        <!-- Order Status -->
                        <td class="py-4">
                            <span class="px-2.5 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase
                                @if($rent->status === 'pending') bg-yellow-950 text-yellow-400 border border-yellow-500/20
                                @elseif($rent->status === 'approved') bg-emerald-950 text-emerald-400 border border-emerald-500/20
                                @elseif($rent->status === 'borrowed') bg-blue-950 text-blue-400 border border-blue-500/20
                                @elseif($rent->status === 'completed') bg-emerald-500/10 text-emerald-300 border border-emerald-500/20
                                @else bg-red-950 text-red-400 border border-red-500/20 @endif">
                                @if($rent->status === 'pending') Menunggu @elseif($rent->status === 'approved') Disetujui @elseif($rent->status === 'borrowed') Dipinjam @elseif($rent->status === 'completed') Selesai @else Ditolak @endif
                            </span>
                        </td>

                        <!-- Action trigger -->
                        <td class="py-4 text-right">
                            <a href="{{ route('admin.rentals.show', $rent->id) }}" class="inline-flex items-center gap-1 font-bold text-emerald-400 hover:underline">
                                Kelola <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-500">Tidak ada data transaksi rental ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    @if($rentals->hasPages())
        <div class="pt-6 border-t border-slate-850">
            {{ $rentals->links() }}
        </div>
    @endif

</div>
@endsection
