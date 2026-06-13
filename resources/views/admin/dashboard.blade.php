@extends('layouts.admin')

@section('page_title', 'Dashboard Ringkasan')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Statistics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat 1: Revenue -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
        <div>
            <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mb-1">Total Pendapatan (Lunas)</p>
            <h3 class="font-display font-extrabold text-2xl text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
        <span class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
            <i data-lucide="banknote" class="h-6 w-6"></i>
        </span>
    </div>

    <!-- Stat 2: Active Orders -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
        <div>
            <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mb-1">Rental Sedang Berjalan</p>
            <h3 class="font-display font-extrabold text-2xl text-white">{{ $activeRentalsCount }}</h3>
        </div>
        <span class="p-3 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20">
            <i data-lucide="calendar" class="h-6 w-6"></i>
        </span>
    </div>

    <!-- Stat 3: Item Outs -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
        <div>
            <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mb-1">Item Outs (Barang Keluar)</p>
            <h3 class="font-display font-extrabold text-2xl text-purple-400">{{ $itemOutsCount }}</h3>
        </div>
        <span class="p-3 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
            <i data-lucide="arrow-up-right" class="h-6 w-6"></i>
        </span>
    </div>

    <!-- Stat 4: New Customers -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
        <div>
            <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mb-1">New Customers (30 Hari)</p>
            <h3 class="font-display font-extrabold text-2xl text-orange-400">{{ $newCustomersCount }}</h3>
        </div>
        <span class="p-3 rounded-xl bg-orange-500/10 text-orange-500 border border-orange-500/20">
            <i data-lucide="user-plus" class="h-6 w-6"></i>
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-8">
    <!-- Chart Column (Left) -->
    <div class="lg:col-span-8 p-6 rounded-2xl bg-slate-900 border border-slate-800 glass space-y-4">
        <div class="flex items-center justify-between border-b border-slate-850 pb-4">
            <h3 class="font-display font-bold text-white text-sm">Tren Pendapatan Bulanan (IDR)</h3>
            <span class="px-2 py-0.5 rounded bg-slate-950 text-[10px] text-slate-500 font-semibold uppercase tracking-wider">REALTIME LOGS</span>
        </div>
        
        <div class="aspect-[2/1] w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Popular products Column (Right) -->
    <div class="lg:col-span-4 p-6 rounded-2xl bg-slate-900 border border-slate-800 glass space-y-4">
        <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-4">Unit Paling Populer</h3>
        
        <div class="divide-y divide-slate-850 space-y-4">
            @forelse($popularProducts as $prod)
                <div class="pt-4 first:pt-0 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-10 bg-slate-950 border border-slate-800 rounded flex items-center justify-center flex-shrink-0 text-slate-600">
                            <i data-lucide="{{ $prod->category->type === 'camera' ? 'camera' : 'tent' }}" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white line-clamp-1">{{ $prod->name }}</h4>
                            <p class="text-[9px] text-slate-500 mt-0.5 uppercase tracking-wide">{{ $prod->category->name }}</p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <span class="inline-flex items-center gap-0.5 text-xs font-bold text-amber-500">
                            <i data-lucide="star" class="h-3 w-3 fill-amber-500"></i> {{ $prod->rating }}
                        </span>
                        <p class="text-[9px] text-slate-500 mt-0.5 font-mono">Rp {{ number_format($prod->price_per_day, 0, ',', '.') }}/h</p>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-500 text-center py-4">Belum ada data barang.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Transactions list -->
<div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
    <div class="flex items-center justify-between mb-6 border-b border-slate-850 pb-4">
        <h3 class="font-display font-bold text-white text-sm">Pemesanan Sewa Terbaru</h3>
        <a href="{{ route('admin.rentals.index') }}" class="text-[10px] text-emerald-400 font-bold hover:underline uppercase tracking-wider flex items-center gap-1">
            Lihat Semua <i data-lucide="arrow-right" class="h-3 w-3"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left border-collapse">
            <thead>
                <tr class="text-slate-500 uppercase font-semibold border-b border-slate-850 pb-3">
                    <th class="pb-3">No. Invoice</th>
                    <th class="pb-3">Penyewa</th>
                    <th class="pb-3">Tanggal Sewa</th>
                    <th class="pb-3">Total Biaya</th>
                    <th class="pb-3">Status Bayar</th>
                    <th class="pb-3">Status Sewa</th>
                    <th class="pb-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-850">
                @forelse($recentTransactions as $rent)
                    <tr class="hover:bg-slate-900/10 transition-colors">
                        <td class="py-4 font-mono font-bold text-white">#{{ $rent->payment->transaction_id ?? $rent->id }}</td>
                        <td class="py-4">
                            <p class="font-bold text-slate-200">{{ $rent->user->name }}</p>
                            <p class="text-[10px] text-slate-500 mt-0.5 font-mono">{{ $rent->user->email }}</p>
                        </td>
                        <td class="py-4">
                            <span class="font-semibold text-slate-300">{{ $rent->start_date->format('d M Y') }} - {{ $rent->end_date->format('d M Y') }}</span>
                            <span class="text-[9px] text-slate-500 block">({{ $rent->total_days }} Hari (24 jam))</span>
                        </td>
                        <td class="py-4 font-bold text-emerald-400">Rp {{ number_format($rent->total_price, 0, ',', '.') }}</td>
                        <td class="py-4">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase
                                @if($rent->payment_status === 'paid') bg-emerald-950 text-emerald-400 border border-emerald-500/20
                                @elseif($rent->payment_status === 'unpaid') bg-yellow-950 text-yellow-400 border border-yellow-500/20
                                @else bg-slate-950 text-slate-500 border border-slate-850 @endif">
                                {{ $rent->payment_status === 'paid' ? 'Paid' : ($rent->payment_status === 'unpaid' ? 'Unpaid' : 'Expired') }}
                            </span>
                        </td>
                        <td class="py-4">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase
                                @if($rent->status === 'pending') bg-yellow-950 text-yellow-400 border border-yellow-500/20
                                @elseif($rent->status === 'approved') bg-emerald-950 text-emerald-400 border border-emerald-500/20
                                @elseif($rent->status === 'borrowed') bg-blue-950 text-blue-400 border border-blue-500/20
                                @elseif($rent->status === 'completed') bg-emerald-500/10 text-emerald-300 border border-emerald-500/20
                                @else bg-red-950 text-red-400 border border-red-500/20 @endif">
                                @if($rent->status === 'pending') Menunggu @elseif($rent->status === 'approved') Disetujui @elseif($rent->status === 'borrowed') Dipinjam @elseif($rent->status === 'completed') Selesai @else Ditolak @endif
                            </span>
                        </td>
                        <td class="py-4 text-right">
                            <a href="{{ route('admin.rentals.show', $rent->id) }}" class="inline-flex items-center gap-0.5 font-bold text-emerald-400 hover:underline">
                                Kelola <i data-lucide="chevron-right" class="h-3 w-3"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-slate-500">Belum ada pemesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart JS Loader Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const labels = {!! json_encode($chartLabels) !!};
        const dataValues = {!! json_encode($chartValues) !!};

        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan Bulanan (Rp)',
                    data: dataValues,
                    borderColor: '#10b981', // Emerald 500
                    backgroundColor: 'rgba(16, 185, 129, 0.05)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#10b981',
                    pointHoverRadius: 6,
                    tension: 0.35,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#1e293b' // Slate 800
                        },
                        ticks: {
                            color: '#64748b', // Slate 500
                            font: {
                                size: 10
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#1e293b'
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 10
                            },
                            callback: function(value) {
                                return 'Rp ' + (value/1000).toLocaleString() + 'k';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
