@extends('layouts.admin')

@section('page_title', 'Konfirmasi Pemesanan')

@section('content')
<div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
    
    <!-- Header with Search -->
    <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-6 border-b border-slate-850 pb-6 mb-6">
        <div>
            <h2 class="font-display font-extrabold text-lg text-white flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <i data-lucide="clipboard-check" class="h-5 w-5"></i>
                </span>
                Konfirmasi Pemesanan pending
            </h2>
            <p class="text-xs text-slate-500 mt-1">Daftar transaksi penyewaan barang baru yang membutuhkan pengecekan barang dan verifikasi bukti pembayaran.</p>
        </div>

        <form action="{{ route('admin.confirmations.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full xl:w-auto text-xs">
            <!-- Search -->
            <div class="relative w-full sm:w-72">
                  <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelanggan, email, ID sewa..." 
                      class="icon-input w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
                <i data-lucide="search" class="absolute left-3.5 top-3 h-3.5 w-3.5 text-slate-500"></i>
            </div>

            <div class="flex items-center gap-2">
                @if(request('search'))
                    <a href="{{ route('admin.confirmations.index') }}" class="px-4 py-2.5 text-center border border-slate-850 hover:bg-slate-950 text-slate-400 hover:text-white rounded-xl font-semibold transition-all">Clear</a>
                @endif
                <button type="submit" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all shadow-md">Apply Search</button>
            </div>
        </form>
    </div>

    <!-- Rentals Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left border-collapse">
            <thead>
                <tr class="text-slate-500 uppercase font-semibold border-b border-slate-850 pb-3">
                    <th class="pb-3">Invoice Ref</th>
                    <th class="pb-3">Customer</th>
                    <th class="pb-3">Periode Sewa</th>
                    <th class="pb-3">Grand Total</th>
                    <th class="pb-3">Metode Bayar</th>
                    <th class="pb-3">Status Bukti</th>
                    <th class="pb-3 text-right">Verifikasi</th>
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

                        <!-- Payment Status / Proof -->
                        <td class="py-4">
                            @php
                                $paymentReady = $rent->payment_method === 'midtrans' ? $rent->payment_status === 'paid' : (!empty($rent->payment) && !empty($rent->payment->payment_proof));
                            @endphp
                            @if($paymentReady)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase bg-emerald-950 text-emerald-400 border border-emerald-500/20">
                                    <i data-lucide="check-circle" class="h-3 w-3"></i> Pembayaran Siap
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase bg-yellow-950 text-yellow-400 border border-yellow-500/20">
                                    <i data-lucide="help-circle" class="h-3 w-3"></i> Menunggu Pembayaran
                                </span>
                            @endif
                            <div class="text-[10px] text-slate-500 mt-1.5 uppercase">
                                {{ $rent->payment_status === 'paid' ? 'Lunas' : ($rent->payment_status === 'unpaid' ? 'Belum Bayar' : 'Expired') }}
                            </div>
                        </td>

                        <!-- Action trigger -->
                        <td class="py-4 text-right">
                            <a href="{{ route('admin.confirmations.show', $rent->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg transition-all shadow shadow-emerald-500/10">
                                Periksa Pemesanan <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-500">
                            <i data-lucide="inbox" class="h-10 w-10 mx-auto text-slate-700 mb-3"></i>
                            <p class="text-xs font-bold text-slate-400">Tidak ada pemesanan baru yang memerlukan konfirmasi.</p>
                            <p class="text-[10px] text-slate-600 mt-1">Semua pesanan baru yang masuk telah selesai diproses.</p>
                        </td>
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
