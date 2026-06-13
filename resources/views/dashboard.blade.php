@extends('layouts.frontend')

@section('title', 'Dashboard Saya - EquipRent')

@section('content')
<section class="py-12 bg-slate-950 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Jumbotron Banner -->
        <div class="p-8 sm:p-10 rounded-3xl bg-slate-900 border border-slate-800 glass relative overflow-hidden mb-10 bg-gradient-to-r from-slate-900 to-slate-950 glow-emerald">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 mb-1 block">CUSTOMER PORTAL</span>
                    <h1 class="font-display font-extrabold text-3xl text-white tracking-tight">
                        Selamat Datang Kembali, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-sm text-slate-400 mt-1">Lacak status penyewaan kamera dan tenda camping Anda di portal interaktif ini.</p>
                </div>
                
                <a href="{{ route('catalog.index') }}" class="px-5 py-3 rounded-xl bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald transition-all text-xs flex items-center gap-2">
                    <i data-lucide="plus" class="h-4 w-4"></i> Sewa Baru
                </a>
            </div>
        </div>

        <!-- Rentals History Panel -->
        <div class="p-8 rounded-3xl bg-slate-900/10 border border-slate-900 glass">
            <h3 class="font-display font-bold text-white text-lg mb-6 flex items-center gap-2">
                <i data-lucide="history" class="text-slate-400 h-5 w-5"></i> Riwayat Transaksi Rental
            </h3>

            @if(!empty($rentals) && $rentals->count() > 0)
                <!-- Responsive list -->
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-900 text-slate-500 uppercase font-semibold">
                                <th class="px-6 py-4">No. Invoice / Ref</th>
                                <th class="px-6 py-4">Periode Sewa</th>
                                <th class="px-6 py-4">Total Biaya</th>
                                <th class="px-6 py-4">Metode Bayar</th>
                                <th class="px-6 py-4">Status Bayar</th>
                                <th class="px-6 py-4">Status Sewa</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentals as $rental)
                                <tr class="border-b border-slate-900 hover:bg-slate-900/20 transition-colors">
                                    <!-- Transaction ID / Invoice -->
                                    <td class="px-6 py-4 font-mono font-bold text-white">
                                        #{{ $rental->payment->transaction_id ?? $rental->id }}
                                    </td>
                                    
                                    <!-- Dates -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-200">
                                            {{ $rental->start_date->format('d M Y') }} - {{ $rental->end_date->format('d M Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 mt-0.5">Durasi: {{ $rental->total_days }} Hari (24 jam)</div>
                                    </td>

                                    <!-- Price -->
                                    <td class="px-6 py-4 font-extrabold text-emerald-400">
                                        Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                                    </td>

                                    <!-- Method -->
                                    <td class="px-6 py-4 uppercase font-semibold text-slate-400">
                                        {{ $rental->payment_method }}
                                    </td>

                                    <!-- Payment Status -->
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider
                                            @if($rental->payment_status === 'paid')
                                                bg-emerald-950/80 text-emerald-400 border border-emerald-500/20
                                            @elseif($rental->payment_status === 'unpaid')
                                                bg-yellow-950/80 text-yellow-400 border border-yellow-500/20
                                            @else
                                                bg-slate-950/80 text-slate-500 border border-slate-800
                                            @endif">
                                            {{ $rental->payment_status === 'paid' ? 'LUNAS' : ($rental->payment_status === 'unpaid' ? 'BELUM BAYAR' : 'EXPIRED') }}
                                        </span>
                                    </td>

                                    <!-- Order status -->
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider
                                            @if($rental->status === 'pending')
                                                bg-yellow-950 text-yellow-400 border border-yellow-500/20
                                            @elseif($rental->status === 'approved')
                                                bg-emerald-950 text-emerald-400 border border-emerald-500/20
                                            @elseif($rental->status === 'borrowed')
                                                bg-blue-950 text-blue-400 border border-blue-500/20
                                            @elseif($rental->status === 'completed')
                                                bg-emerald-500/10 text-emerald-300 border border-emerald-500/20
                                            @else
                                                bg-red-950 text-red-400 border border-red-500/20
                                            @endif">
                                            @if($rental->status === 'pending') Menunggu @elseif($rental->status === 'approved') Disetujui @elseif($rental->status === 'borrowed') Dipinjam @elseif($rental->status === 'completed') Selesai @else Ditolak @endif
                                        </span>
                                    </td>

                                    <!-- Detail Action -->
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('dashboard.rental', $rental->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-800 hover:border-emerald-500 text-slate-300 hover:text-emerald-400 hover:bg-slate-900 transition-all font-bold">
                                            Detail <i data-lucide="arrow-right" class="h-3 w-3"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16 text-slate-500">
                    <i data-lucide="shopping-bag" class="h-10 w-10 text-slate-700 mx-auto mb-4"></i>
                    <p class="text-sm">Anda belum pernah melakukan pemesanan sewa gear.</p>
                    <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 mt-2 block underline">Sewa Kamera & Outdoor Alat Sekarang</a>
                </div>
            @endif

        </div>

    </div>
</section>
@endsection
