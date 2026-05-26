@extends('layouts.frontend')

@section('title', 'Keranjang Booking Rental Gear Anda')

@section('content')
<section class="py-12 bg-slate-950 min-h-[75vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Headers -->
        <div class="border-b border-slate-900 pb-6 mb-10">
            <h1 class="font-display font-extrabold text-3xl text-white flex items-center gap-3">
                <i data-lucide="shopping-cart" class="text-emerald-400"></i> Keranjang Sewa Anda
            </h1>
            <p class="text-xs text-slate-500 mt-2">Daftar perlengkapan yang ingin Anda sewa. Pastikan periode tanggal sewa sudah tepat sebelum melanjutkan.</p>
        </div>

        @if(!empty($cart) && count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <!-- Items list (Left) -->
                <div class="lg:col-span-8 space-y-6">
                    @foreach($cart as $item)
                        <!-- Cart Item Card -->
                        <div class="p-6 rounded-2xl bg-slate-900/30 border border-slate-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:border-slate-800 transition-colors">
                            
                            <!-- Product Details & Thumb -->
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-20 rounded-xl bg-slate-950 border border-slate-900 flex items-center justify-center flex-shrink-0 relative bg-gradient-to-br from-slate-900 to-slate-950">
                                    <i data-lucide="package" class="h-6 w-6 text-slate-700"></i>
                                </div>
                                <div>
                                    <h3 class="font-display font-bold text-white text-sm hover:text-emerald-400 transition-colors">
                                        {{ $item['name'] }}
                                    </h3>
                                    <!-- Price / day -->
                                    <p class="text-xs text-slate-500 mt-0.5">Rp {{ number_format($item['price_per_day'], 0, ',', '.') }} / hari</p>
                                </div>
                            </div>

                            <!-- Renting Period Details -->
                            <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-900 grid grid-cols-2 gap-x-6 gap-y-1 text-[11px] w-full sm:w-auto">
                                <div class="text-slate-500">Mulai:</div>
                                <div class="text-white font-bold">{{ \Carbon\Carbon::parse($item['start_date'])->format('d M Y') }}</div>
                                <div class="text-slate-500">Selesai:</div>
                                <div class="text-white font-bold">{{ \Carbon\Carbon::parse($item['end_date'])->format('d M Y') }}</div>
                                <div class="text-slate-500">Durasi:</div>
                                <div class="text-emerald-400 font-extrabold">{{ $item['total_days'] }} Hari</div>
                            </div>

                            <!-- Subtotal and Action -->
                            <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center w-full sm:w-auto border-t sm:border-t-0 border-slate-900 pt-4 sm:pt-0">
                                <div class="text-left sm:text-right mb-0.5">
                                    <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Subtotal</p>
                                    <p class="text-sm font-extrabold text-emerald-400">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                </div>
                                
                                <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-slate-900/60 hover:bg-red-950/40 text-slate-400 hover:text-red-400 border border-slate-900 hover:border-red-900/20 transition-all" title="Hapus Barang">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach

                    <!-- Options below cart -->
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition-colors flex items-center gap-1">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i> Tambah Peralatan Lain
                        </a>
                        
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white transition-colors border border-slate-800 flex items-center gap-1.5">
                                <i data-lucide="refresh-cw" class="h-3.5 w-3.5"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Grand Total Card (Right) -->
                <div class="lg:col-span-4">
                    <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 glass space-y-6">
                        <h3 class="font-display font-bold text-white text-base border-b border-slate-800 pb-3">Ringkasan Penyewaan</h3>
                        
                        <div class="space-y-3 text-xs text-slate-400">
                            <div class="flex justify-between">
                                <span>Total Item:</span>
                                <span class="text-white font-bold">{{ count($cart) }} Perlengkapan</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Sewa Bersih:</span>
                                <span class="text-white">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Layanan & Cek Steril:</span>
                                <span class="text-emerald-400 font-semibold font-mono">GRATIS / FREE</span>
                            </div>
                            <hr class="border-slate-800 my-4">
                            <div class="flex justify-between text-sm font-bold text-white">
                                <span class="text-emerald-400">Total Pembayaran:</span>
                                <span class="text-emerald-400 font-extrabold text-base">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-900 text-[10px] text-slate-500 leading-relaxed flex gap-2">
                            <i data-lucide="shield-check" class="h-5 w-5 text-emerald-400 flex-shrink-0 mt-0.5"></i>
                            <p>Dengan menekan tombol checkout, Anda menyetujui syarat & ketentuan pengembalian barang dalam keadaan bersih dan tepat waktu.</p>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="w-full py-4 rounded-xl font-bold bg-emerald-500 text-slate-950 hover:bg-emerald-400 hover-glow-emerald transition-all text-center flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10">
                            Lanjut ke Checkout <i data-lucide="chevron-right" class="h-5 w-5"></i>
                        </a>
                    </div>
                </div>

            </div>
        @else
            <!-- Empty state -->
            <div class="max-w-md mx-auto text-center py-20 bg-slate-900/10 border border-slate-900 rounded-3xl p-8 glass">
                <div class="h-16 w-16 bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-600 border border-slate-800">
                    <i data-lucide="shopping-cart" class="h-8 w-8"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-white mb-2">Keranjang Booking Kosong</h3>
                <p class="text-xs text-slate-400 mb-8 leading-relaxed">Anda belum menambahkan perlengkapan sewa kamera atau camping. Mulai jelajahi inventaris premium kami sekarang juga!</p>
                <a href="{{ route('catalog.index') }}" class="px-6 py-3.5 rounded-xl bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald transition-all text-xs inline-flex items-center gap-2">
                    Jelajahi Katalog <i data-lucide="binoculars" class="h-4.5 w-4.5"></i>
                </a>
            </div>
        @endif

    </div>
</section>
@endsection
