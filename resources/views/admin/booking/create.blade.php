@extends('layouts.admin')

@section('page_title', 'Buat Booking Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-8" x-data="bookingCreator()">

    <!-- Top Toolbar Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.bookings.index') }}" class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
        </a>
        <div>
            <h2 class="font-display font-extrabold text-2xl text-white">Create Booking</h2>
            <p class="text-xs text-slate-500 mt-1">Buat penyewaan langsung untuk customer melalui panel administrator.</p>
        </div>
    </div>

    <!-- Main Creation Form Panel -->
    <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 glass shadow-xl">
        <form action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <!-- 1. Customer Search / Select -->
            <div>
                <label for="user_id" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Pilih Customer</label>
                <select id="user_id" name="user_id" required class="w-full bg-slate-950 border border-slate-850 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                    <option value="" disabled selected>-- Pilih Customer --</option>
                    @foreach($customers as $cust)
                        <option value="{{ $cust->id }}" {{ old('user_id') == $cust->id ? 'selected' : '' }}>
                            {{ $cust->name }} ({{ $cust->email }}) - Telp: {{ $cust->phone ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 2. Product Selection -->
            <div>
                <label for="product_id" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Pilih Perlengkapan Sewa</label>
                <select id="product_id" name="product_id" required @change="updateProductSelected" class="w-full bg-slate-950 border border-slate-850 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                    <option value="" disabled selected>-- Pilih Barang --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" 
                                data-name="{{ $prod->name }}" 
                                data-price="{{ (int)$prod->price_per_day }}"
                                data-stock="{{ $prod->stock }}"
                                data-thumb="{{ asset($prod->thumbnail_url) }}"
                                {{ old('product_id') == $prod->id ? 'selected' : '' }}>
                            #{{ $prod->id }} - {{ $prod->name }} (Rp {{ number_format($prod->price_per_day, 0, ',', '.') }}/hari) - Sisa Stok: {{ $prod->stock }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Product Preview Card -->
            <div x-show="selectedProduct.name" class="p-4 rounded-2xl bg-slate-950 border border-slate-850 flex items-center gap-4 transition-all" style="display: none;">
                <img :src="selectedProduct.thumb" alt="" class="h-14 w-16 rounded object-cover border border-slate-800 bg-slate-900">
                <div>
                    <h4 class="font-bold text-white text-sm" x-text="selectedProduct.name"></h4>
                    <p class="text-[10px] text-slate-500 mt-1">
                        Harga Sewa: <span class="text-emerald-400 font-bold">Rp <span x-text="formatNumber(selectedProduct.price)"></span>/hari</span> | 
                        Stok Maksimal: <span class="text-slate-300 font-bold" x-text="selectedProduct.stock"></span> unit
                    </p>
                </div>
            </div>

            <!-- 3. Rental Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" required min="{{ date('Y-m-d') }}" 
                           x-model="dates.start" @change="calculateSummary"
                           class="w-full bg-slate-950 border border-slate-850 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                </div>
                <div>
                    <label for="end_date" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" required :min="dates.start"
                           x-model="dates.end" @change="calculateSummary"
                           class="w-full bg-slate-950 border border-slate-850 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                </div>
            </div>

            <!-- Note -->
            <div>
                <label for="note" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Catatan Booking (Opsional)</label>
                <textarea id="note" name="note" rows="2" placeholder="Catatan khusus dari admin atau customer..."
                          class="w-full bg-slate-950 border border-slate-850 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none resize-none"></textarea>
            </div>

            <!-- 4. Interactive Booking Summary -->
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-850 space-y-4">
                <h3 class="font-display font-bold text-white text-xs border-b border-slate-900 pb-2.5">Ringkasan Booking</h3>
                
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Nama Barang</span>
                        <span class="font-bold text-white" x-text="selectedProduct.name || '-'"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Durasi Sewa</span>
                        <span class="font-bold text-white"><span x-text="summary.totalDays"></span> Hari (24 jam)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Perhitungan Harga</span>
                        <span class="font-bold text-slate-300">
                            Rp <span x-text="formatNumber(selectedProduct.price)"></span> x <span x-text="summary.totalDays"></span> Hari (24 jam)
                        </span>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-900 pt-3">
                        <span class="font-bold text-white">Total Harga</span>
                        <span class="font-display font-extrabold text-lg text-emerald-400">
                            Rp <span x-text="formatNumber(summary.totalPrice)"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-slate-850 flex justify-end gap-3">
                <a href="{{ route('admin.bookings.index') }}" class="px-5 py-3 rounded-xl border border-slate-850 hover:bg-slate-950 font-bold transition-all text-center">Batal</a>
                <button type="submit" class="px-5 py-3 bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald rounded-xl transition-all shadow-md">
                    Konfirmasi & Buat Booking
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Alpine.js Application Logic -->
<script>
    function bookingCreator() {
        return {
            selectedProduct: {
                name: '',
                price: 0,
                stock: 0,
                thumb: ''
            },
            dates: {
                start: '',
                end: ''
            },
            summary: {
                totalDays: 0,
                totalPrice: 0
            },
            updateProductSelected(e) {
                const opt = e.target.options[e.target.selectedIndex];
                if (opt.value) {
                    this.selectedProduct.name = opt.getAttribute('data-name');
                    this.selectedProduct.price = parseInt(opt.getAttribute('data-price')) || 0;
                    this.selectedProduct.stock = parseInt(opt.getAttribute('data-stock')) || 0;
                    this.selectedProduct.thumb = opt.getAttribute('data-thumb');
                } else {
                    this.selectedProduct.name = '';
                    this.selectedProduct.price = 0;
                    this.selectedProduct.stock = 0;
                    this.selectedProduct.thumb = '';
                }
                this.calculateSummary();
            },
            calculateSummary() {
                if (this.dates.start && this.dates.end && this.selectedProduct.price) {
                    const start = new Date(this.dates.start);
                    const end = new Date(this.dates.end);
                    if (end >= start) {
                            const diffTime = Math.max(0, end - start);
                            const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
                        this.summary.totalDays = diffDays;
                        this.summary.totalPrice = diffDays * this.selectedProduct.price;
                        return;
                    }
                }
                this.summary.totalDays = 0;
                this.summary.totalPrice = 0;
            },
            formatNumber(num) {
                if (!num) return '0';
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        }
    }
</script>
@endsection
