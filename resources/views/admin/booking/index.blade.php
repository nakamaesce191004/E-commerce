@extends('layouts.admin')

@section('page_title', 'Kelola Booking / Sewa')

@section('content')
<div class="space-y-8">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-display font-extrabold text-2xl text-white">Daftar Booking Alat</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh perlengkapan yang sedang disewa atau dalam status pemesanan.</p>
        </div>
        
        <a href="{{ route('admin.bookings.create') }}" class="px-5 py-3 rounded-xl bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald transition-all text-xs flex items-center gap-2">
            <i data-lucide="plus" class="h-4 w-4"></i> Create Booking
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                  <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama penyewa or barang..." 
                      class="icon-input w-full bg-slate-950 border border-slate-850 focus:border-emerald-500 rounded-xl pl-9 pr-4 py-2 text-xs text-white focus:outline-none">
                <span class="absolute left-3 top-2.5 text-slate-500">
                    <i data-lucide="search" class="h-4 w-4"></i>
                </span>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-950 border border-slate-850 hover:bg-slate-800 hover:text-white text-slate-400 font-bold rounded-xl transition-all">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 border border-slate-850 hover:bg-slate-950 text-slate-400 hover:text-white rounded-xl font-semibold transition-all">Clear</a>
            @endif
        </form>
    </div>

    <!-- Bookings List Table -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
        <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-4 mb-6 flex items-center gap-2">
            <i data-lucide="calendar" class="text-slate-400 h-4.5 w-4.5"></i> Barang yang Dipinjam / Dipesan
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="text-slate-500 uppercase font-semibold border-b border-slate-850 pb-3">
                        <th class="pb-3 w-16">Gambar</th>
                        <th class="pb-3 w-20">ID Barang</th>
                        <th class="pb-3">Nama Customer</th>
                        <th class="pb-3">Barang yang Dipinjam</th>
                        <th class="pb-3">Harga x Hari (Total)</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($bookings as $item)
                        @php
                            $rental = $item->rental;
                            $product = $item->product;
                        @endphp
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <!-- Product Image -->
                            <td class="py-4">
                                <div class="h-10 w-12 rounded bg-slate-950 border border-slate-850 flex items-center justify-center overflow-hidden">
                                    @if($product->thumbnail_url)
                                        <img src="{{ asset($product->thumbnail_url) }}" alt="{{ $product->name }}" class="object-cover h-full w-full">
                                    @else
                                        <i data-lucide="package" class="h-4 w-4 text-slate-600"></i>
                                    @endif
                                </div>
                            </td>
                            <!-- Product ID -->
                            <td class="py-4 font-mono font-bold text-slate-400">#{{ $product->id }}</td>
                            <!-- Customer Name -->
                            <td class="py-4">
                                <p class="font-bold text-white">{{ $rental->user->name ?? 'N/A' }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">{{ $rental->user->email ?? '' }}</p>
                            </td>
                            <!-- Product Name & Dates -->
                            <td class="py-4">
                                <p class="font-bold text-slate-200">{{ $product->name }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">
                                    {{ $rental->start_date->format('d M Y') }} s/d {{ $rental->end_date->format('d M Y') }} ({{ $rental->total_days }} Hari)
                                </p>
                            </td>
                            <!-- Price x Days -->
                            <td class="py-4">
                                <p class="font-bold text-emerald-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                <p class="text-[9px] text-slate-500 mt-0.5 font-mono">
                                    Rp {{ number_format($item->price_per_day, 0, ',', '.') }}/hari x {{ $rental->total_days }} hari
                                </p>
                            </td>
                            <!-- Status -->
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase
                                    @if($rental->status === 'pending') bg-yellow-950 text-yellow-400 border border-yellow-500/20
                                    @elseif($rental->status === 'approved') bg-emerald-950 text-emerald-400 border border-emerald-500/20
                                    @elseif($rental->status === 'borrowed') bg-blue-950 text-blue-400 border border-blue-500/20
                                    @elseif($rental->status === 'completed') bg-emerald-500/10 text-emerald-300 border border-emerald-500/20
                                    @else bg-red-950 text-red-400 border border-red-500/20 @endif">
                                    @if($rental->status === 'pending') Menunggu @elseif($rental->status === 'approved') Disetujui @elseif($rental->status === 'borrowed') Dipinjam @elseif($rental->status === 'completed') Selesai @else Ditolak @endif
                                </span>
                            </td>
                            <!-- View Details -->
                            <td class="py-4 text-right">
                                <a href="{{ route('admin.bookings.show', $rental->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-950 hover:bg-slate-800 text-slate-300 font-bold rounded-lg border border-slate-800 hover:border-slate-700 transition-all">
                                    <i data-lucide="eye" class="h-3.5 w-3.5"></i> View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-500">Belum ada barang sewa yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="pt-6 border-t border-slate-850">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
