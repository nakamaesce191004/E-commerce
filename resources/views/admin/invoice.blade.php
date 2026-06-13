<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $rental->payment->transaction_id ?? $rental->id }} - EquipRent</title>

    <!-- Tailwind CSS (for styled print layouts) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @media print {
            body {
                background-color: #ffffff;
                color: #1e293b;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen py-10 antialiased">

    <!-- Top floating bar (Not shown on print) -->
    <div class="max-w-4xl mx-auto mb-6 px-4 no-print flex justify-between items-center bg-slate-900 border border-slate-850 p-4 rounded-2xl shadow-md">
        <span class="text-xs font-semibold text-slate-400">Pratinjau Invoice Peminjaman</span>
        <div class="flex items-center gap-3">
            <button onclick="window.history.back()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-200 rounded-xl transition-all">Kembali</button>
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-xs font-bold text-slate-950 rounded-xl transition-all shadow-md">Cetak / Save PDF</button>
        </div>
    </div>

    <!-- Main Printable Paper Page Frame -->
    <div class="max-w-4xl mx-auto bg-white border border-slate-200 shadow-lg rounded-3xl p-8 sm:p-12 relative">
        
        <!-- Header: Brand logo & Merch info -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-b border-slate-100 pb-8 mb-8">
            <div class="space-y-3">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <span class="p-2 rounded-lg bg-emerald-500 text-white font-extrabold text-sm shadow-md">ER</span>
                    <span class="font-extrabold text-2xl tracking-tight text-slate-900">
                        Equip<span class="text-emerald-600">Rent</span>
                    </span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs">
                    HQ EquipRent, Kebayoran Baru, Jakarta Selatan, 12110 <br>
                    WA Hotline: +62 812-3456-7890 | support@equiprent.com
                </p>
            </div>
            
            <div class="text-left sm:text-right space-y-1.5">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">INVOICE SEWA</h1>
                <p class="text-xs font-mono text-slate-500">No. Tagihan: #{{ $rental->payment->transaction_id ?? $rental->id }}</p>
                <div class="grid grid-cols-2 gap-x-4 text-xs pt-2">
                    <span class="text-slate-400 sm:text-right">Tgl Terbit:</span>
                    <span class="text-slate-700 font-bold sm:text-right">{{ $rental->created_at->format('d M Y, H:i') }} WIB</span>
                    <span class="text-slate-400 sm:text-right">Metode Bayar:</span>
                    <span class="text-slate-700 font-bold uppercase sm:text-right">{{ $rental->payment_method }}</span>
                </div>
            </div>
        </div>

        <!-- Billed To Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 border-b border-slate-100 pb-8 mb-8 text-xs">
            <div class="space-y-2">
                <p class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Ditagihkan Kepada (Renter)</p>
                <p class="text-sm font-bold text-slate-900">{{ $rental->user->name }}</p>
                <p class="text-slate-600 font-mono">{{ $rental->user->email }}</p>
                <p class="text-slate-600 font-mono">No. WhatsApp: {{ $rental->phone }}</p>
            </div>
            
            <div class="space-y-2">
                <p class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Periode Sewa</p>
                <div class="pt-2 text-[10px] text-slate-500 flex gap-2">
                    <span>Mulai: <strong class="text-slate-800">{{ $rental->start_date->format('d M Y') }}</strong></span>
                    <span>|</span>
                    <span>Selesai: <strong class="text-slate-800">{{ $rental->end_date->format('d M Y') }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="mb-8 overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse border-b border-slate-100">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-y border-slate-100">
                        <th class="px-4 py-3">Nama Unit Perlengkapan</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3 text-right">Tarif / Hari</th>
                        <th class="px-4 py-3 text-center">Durasi</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rental->items as $item)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4 font-bold text-slate-900">{{ $item->product->name }}</td>
                            <td class="px-4 py-4 text-slate-500">{{ $item->product->category->name }}</td>
                            <td class="px-4 py-4 text-right text-slate-600">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 text-center text-slate-700 font-bold">{{ $rental->total_days }} Hari (24 jam)</td>
                            <td class="px-4 py-4 text-right font-bold text-slate-950">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Billing totals summary -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-6 mb-12 text-xs">
            <!-- Payment terms -->
            <div class="max-w-sm space-y-2 leading-relaxed text-slate-500">
                <p class="font-bold text-slate-800 uppercase text-[9px] tracking-wider">Syarat & Ketentuan Sewa:</p>
                <ul class="list-disc pl-4 space-y-1 text-[10px]">
                    <li>Peralatan wajib dikembalikan dalam kondisi bersih dan lengkap sesuai daftar serah terima.</li>
                    <li>Keterlambatan pengembalian dikenakan denda akumulasi sebesar tarif harian sewa per unit per hari.</li>
                    <li>Kerusakan atau kehilangan unit alat sepenuhnya menjadi tanggung jawab penyewa sesuai biaya perbaikan resmi.</li>
                </ul>
            </div>
            
            <!-- Calculations -->
            <div class="w-full sm:w-64 space-y-2.5">
                <div class="flex justify-between text-slate-500">
                    <span>Subtotal Sewa:</span>
                    <span class="text-slate-800">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Layanan & Sterilisasi:</span>
                    <span class="text-emerald-600 font-bold font-mono">GRATIS</span>
                </div>
                <hr class="border-slate-100 my-2">
                <div class="flex justify-between text-sm font-extrabold text-slate-900">
                    <span>Total Pembayaran:</span>
                    <span class="text-emerald-600 text-base">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="text-right pt-2">
                    <span class="px-2.5 py-1 rounded text-[9px] font-extrabold uppercase tracking-wider
                        @if($rental->payment_status === 'paid') bg-emerald-100 text-emerald-700
                        @elseif($rental->payment_status === 'unpaid') bg-yellow-100 text-yellow-700
                        @else bg-slate-100 text-slate-500 @endif">
                        Status: {{ $rental->payment_status === 'paid' ? 'LUNAS' : ($rental->payment_status === 'unpaid' ? 'BELUM LUNAS' : 'EXPIRED') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Footer Sign stamp -->
        <div class="flex justify-between items-end border-t border-slate-100 pt-8 mt-12 text-[10px] text-slate-400">
            <div>
                <p>Terima kasih atas kepercayaan Anda menyewa di EquipRent!</p>
                <p class="font-mono mt-1">Sistem Terverifikasi Digital | Keamanan Terjamin</p>
            </div>
            
            <div class="text-center w-40 space-y-12">
                <p class="text-slate-500">Hormat Kami,</p>
                <div>
                    <hr class="border-slate-200">
                    <p class="mt-1.5 font-bold text-slate-800 uppercase tracking-wide">EquipRent Admin</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Automated PDF/Print chimer -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 600);
        }
    </script>
</body>
</html>
