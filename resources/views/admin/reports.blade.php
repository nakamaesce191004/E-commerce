@extends('layouts.admin')

@section('page_title', 'Laporan Keuangan Rental')

@section('content')
<div class="space-y-6">

    <!-- Filters header Card -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
        <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-4 mb-6 flex items-center gap-2">
            <i data-lucide="filter" class="text-slate-400 h-4.5 w-4.5"></i> Rentang Tanggal Laporan Keuangan
        </h3>

        <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-end text-xs">
            <div>
                <label for="start_date" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" required 
                       class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>
            <div>
                <label for="end_date" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" required 
                       class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.index') }}" class="w-1/3 py-3 text-center border border-slate-850 hover:bg-slate-950 text-slate-400 hover:text-white rounded-xl font-bold transition-all">Clear</a>
                <button type="submit" class="w-2/3 py-3 bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald rounded-xl transition-all shadow-md">Filter Laporan</button>
            </div>
        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 flex justify-between items-center">
            <div>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">Total Pendapatan (Rentang Terpilih)</p>
                <h2 class="font-display font-extrabold text-3xl text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
            <span class="p-3.5 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <i data-lucide="banknote" class="h-6 w-6"></i>
            </span>
        </div>

        <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 flex justify-between items-center">
            <div>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">Pemesanan Lunas Terbayar</p>
                <h2 class="font-display font-extrabold text-3xl text-white">{{ $totalOrders }} Pemesanan</h2>
            </div>
            <span class="p-3.5 rounded-xl bg-slate-900 text-slate-400 border border-slate-800">
                <i data-lucide="check-square" class="h-6 w-6"></i>
            </span>
        </div>
    </div>

    <!-- Audited Ledger list -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
        <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-4 mb-6 flex items-center gap-2">
            <i data-lucide="book-open" class="text-slate-400 h-4.5 w-4.5"></i> Buku Kas Hasil Sewa Lunas
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="text-slate-500 uppercase font-semibold border-b border-slate-850 pb-3">
                        <th class="pb-3">Tanggal Lunas</th>
                        <th class="pb-3">No. Invoice</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Durasi</th>
                        <th class="pb-3">Metode Bayar</th>
                        <th class="pb-3 text-right">Pendapatan Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($rentals as $rent)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 text-slate-300">{{ $rent->updated_at->format('d M Y, H:i') }} WIB</td>
                            <td class="py-4 font-mono font-bold text-white">#{{ $rent->payment->transaction_id ?? $rent->id }}</td>
                            <td class="py-4">
                                <p class="font-bold text-slate-200">{{ $rent->user->name }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5 font-mono">{{ $rent->user->email }}</p>
                            </td>
                            <td class="py-4 font-semibold text-slate-400">{{ $rent->total_days }} Hari sewa</td>
                            <td class="py-4 uppercase text-slate-400 font-semibold">{{ $rent->payment_method }}</td>
                            <td class="py-4 text-right font-extrabold text-emerald-400">Rp {{ number_format($rent->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-500">Tidak ada transaksi sewa lunas pada rentang tanggal terpilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
