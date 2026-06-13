@extends('layouts.frontend')

@section('title', $product->name)

@section('content')
<!-- Flatpickr CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<section class="py-12 bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex text-xs text-slate-500 gap-2 mb-8 items-center">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <i data-lucide="chevron-right" class="h-3 w-3"></i>
            <a href="{{ route('catalog.index') }}" class="hover:text-white transition-colors">Katalog</a>
            <i data-lucide="chevron-right" class="h-3 w-3"></i>
            <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="hover:text-white transition-colors">{{ $product->category->name }}</a>
            <i data-lucide="chevron-right" class="h-3 w-3"></i>
            <span class="text-slate-300 font-semibold truncate">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- 1. Product Image Gallery (Left) -->
            <div class="lg:col-span-6 space-y-4" x-data="{ 
                activeImage: '{{ $product->thumbnail_url ? asset($product->thumbnail_url) : '' }}',
                gallery: {!! json_encode($product->gallery_urls ?? []) !!}
             }">
                <!-- Primary Image -->
                <div class="aspect-[4/3] w-full rounded-2xl bg-slate-900 overflow-hidden border border-slate-900 flex items-center justify-center relative bg-gradient-to-br from-slate-900 to-slate-950">
                    <template x-if="activeImage">
                        <img :src="activeImage" alt="{{ $product->name }}" class="h-full w-full object-cover">
                    </template>
                    <template x-if="!activeImage">
                        <i data-lucide="image" class="h-16 w-16 text-slate-800"></i>
                    </template>
                </div>

                <!-- Thumbnail sliders -->
                @if(!empty($product->gallery_urls) && count($product->gallery_urls) > 0)
                    <div class="grid grid-cols-4 gap-3">
                        <!-- Thumbnail 1 (Default thumb) -->
                        <button @click="activeImage = '{{ asset($product->thumbnail_url) }}'" 
                                class="aspect-[4/3] rounded-xl bg-slate-900 border overflow-hidden p-1 flex items-center justify-center transition-all"
                                :class="activeImage === '{{ asset($product->thumbnail_url) }}' ? 'border-emerald-500 bg-emerald-500/5' : 'border-slate-800 hover:border-slate-700'">
                            <img src="{{ asset($product->thumbnail_url) }}" alt="{{ $product->name }}" class="h-full w-full object-cover rounded-lg">
                        </button>
                        @foreach($product->gallery_urls as $index => $image)
                            <button @click="activeImage = '{{ asset($image) }}'" 
                                    class="aspect-[4/3] rounded-xl bg-slate-900 border overflow-hidden p-1 flex items-center justify-center transition-all"
                                    :class="activeImage === '{{ asset($image) }}' ? 'border-emerald-500 bg-emerald-500/5' : 'border-slate-800 hover:border-slate-700'">
                                <img src="{{ asset($image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover rounded-lg">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 2. Product Ordering & Details Panel (Right) -->
            <div class="lg:col-span-6 space-y-8">
                
                <!-- Headers -->
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 rounded-full bg-slate-900 text-emerald-400 font-semibold text-xs border border-slate-800">
                            {{ $product->category->name }}
                        </span>
                        
                        <span class="px-2 py-0.5 text-xs rounded-md font-bold uppercase tracking-wider {{ $product->status === 'available' ? 'bg-emerald-950 text-emerald-400 border border-emerald-500/20' : 'bg-orange-950 text-orange-400 border border-orange-500/20' }}">
                            {{ $product->status === 'available' ? 'Tersedia' : 'Sedang Disewa' }}
                        </span>
                    </div>

                    <h1 class="font-display font-extrabold text-3xl text-white tracking-tight mb-3">
                        {{ $product->name }}
                    </h1>

                    <!-- Ratings -->
                    <div class="flex items-center gap-3 text-sm">
                        <div class="flex items-center gap-1 text-amber-500">
                            <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                            <span class="font-bold text-slate-200">{{ $product->rating }}</span>
                        </div>
                        <span class="text-slate-600">|</span>
                        <a href="#reviews" class="text-slate-400 hover:text-emerald-400 transition-colors">{{ $product->reviews()->count() }} Ulasan Pelanggan</a>
                    </div>
                </div>

                <!-- Price and Specs summary -->
                <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-900 flex justify-between items-center bg-gradient-to-r from-slate-900/50 to-slate-950">
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mb-0.5">Biaya Sewa Harian</p>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-3xl font-extrabold text-emerald-400">Rp {{ number_format($product->price_per_day, 0, ',', '.') }}</span>
                            <span class="text-xs text-slate-500">/ Hari</span>
                        </div>
                    </div>
                    
                    <!-- Wishlist Toggler -->
                    @auth
                        <form action="{{ route('wishlist.toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="h-12 w-12 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-red-500 border border-slate-800 transition-all flex items-center justify-center" title="Simpan ke Wishlist">
                                <i data-lucide="heart" class="h-5 w-5 {{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'fill-red-500 text-red-500' : '' }}"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="h-12 w-12 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-red-500 border border-slate-800 transition-all flex items-center justify-center" title="Simpan ke Wishlist">
                            <i data-lucide="heart" class="h-5 w-5"></i>
                        </a>
                    @endauth
                </div>

                <!-- Stock & Denda details -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-900/20 border border-slate-900 flex items-center gap-3">
                        <div class="p-2.5 rounded-lg bg-emerald-500/10 text-emerald-400">
                            <i data-lucide="package" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-bold">Stok Tersedia</p>
                            <p class="text-sm font-extrabold text-slate-200">{{ $product->stock }} Unit</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-900/20 border border-slate-900 flex items-center gap-3">
                        <div class="p-2.5 rounded-lg bg-rose-500/10 text-rose-400">
                            <i data-lucide="alert-triangle" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-bold">Denda Keterlambatan</p>
                            <p class="text-sm font-extrabold text-rose-400">Rp {{ number_format($product->denda_per_day, 0, ',', '.') }} <span class="text-[10px] font-normal text-slate-500">/ Hari</span></p>
                        </div>
                    </div>
                </div>

                <!-- Booking Input Widget Form (Flatpickr & JS Calc) -->
                @if($product->status === 'available')
                    <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 shadow-xl glass space-y-6" x-data="{ dateSelected: false }">
                        <h3 class="font-display font-bold text-white text-base flex items-center gap-2 border-b border-slate-800 pb-3">
                            <i data-lucide="calendar" class="h-5 w-5 text-emerald-400"></i> Atur Periode Penyewaan
                        </h3>

                        <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Tanggal & Jam Mulai</label>
                                    <div class="relative">
                                             <input type="text" name="start_date" id="start_date" required placeholder="Pilih Tanggal & Jam" 
                                                 class="icon-input w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500 cursor-pointer">
                                        <i data-lucide="calendar" class="absolute left-3.5 top-3 h-4 w-4 text-slate-500 pointer-events-none"></i>
                                    </div>
                                </div>
                                <div>
                                    <label for="end_date" class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Tanggal & Jam Selesai</label>
                                    <div class="relative">
                                             <input type="text" name="end_date" id="end_date" required placeholder="Pilih Tanggal & Jam" 
                                                 class="icon-input w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500 cursor-pointer">
                                        <i data-lucide="calendar" class="absolute left-3.5 top-3 h-4 w-4 text-slate-500 pointer-events-none"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Real-time Cost Estimation Display Container -->
                            <div id="cost-calc-box" class="hidden p-4 rounded-xl bg-slate-950 border border-slate-900 space-y-2 text-xs">
                                <div class="flex justify-between text-slate-400">
                                    <span>Durasi Sewa:</span>
                                    <span class="text-white font-bold" id="calc-duration">- Hari</span>
                                </div>
                                <div class="flex justify-between text-slate-400">
                                    <span>Tarif Harian:</span>
                                    <span class="text-white">Rp {{ number_format($product->price_per_day, 0, ',', '.') }}</span>
                                </div>
                                <hr class="border-slate-900 my-1">
                                <div class="flex justify-between text-slate-300 font-bold">
                                    <span class="text-emerald-400">Estimasi Total:</span>
                                    <span class="text-emerald-400 text-sm" id="calc-total">Rp -</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 rounded-xl font-bold bg-emerald-500 text-slate-950 hover:bg-emerald-400 hover-glow-emerald transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10">
                                <i data-lucide="shopping-cart" class="h-5 w-5"></i> Masukkan ke Keranjang
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-6 rounded-2xl bg-orange-950/20 border border-orange-900/30 text-center space-y-3">
                        <i data-lucide="alert-circle" class="h-10 w-10 text-orange-500 mx-auto"></i>
                        <h3 class="font-bold text-white text-base">Maaf, Peralatan Sedang Disewa</h3>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto leading-relaxed">
                            Produk ini sedang tidak tersedia untuk saat ini karena sedang disewa pengguna lain atau dalam proses pemeliharaan berkala. Silakan kembali lagi nanti atau hubungi admin.
                        </p>
                    </div>
                @endif

            </div>

        </div>

        <!-- Tabbed Information (Description & Tabular Specs) -->
        <div class="mt-20 border-t border-slate-900 pt-12" x-data="{ activeTab: 'specs' }">
            <!-- Tab buttons -->
            <div class="flex gap-6 border-b border-slate-900 mb-8">
                <button @click="activeTab = 'specs'" 
                        class="pb-4 text-sm font-semibold tracking-wide uppercase border-b-2 transition-colors focus:outline-none"
                        :class="activeTab === 'specs' ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-400 hover:text-slate-200'">
                    Spesifikasi Teknis
                </button>
                <button @click="activeTab = 'desc'" 
                        class="pb-4 text-sm font-semibold tracking-wide uppercase border-b-2 transition-colors focus:outline-none"
                        :class="activeTab === 'desc' ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-400 hover:text-slate-200'">
                    Deskripsi Lengkap
                </button>
            </div>

            <!-- Tab Content: Specs -->
            <div x-show="activeTab === 'specs'" class="space-y-4" style="display: none;">
                @if(!empty($product->specifications))
                    <div class="max-w-2xl rounded-2xl border border-slate-900 overflow-hidden">
                        <table class="w-full text-sm text-left border-collapse">
                            <tbody>
                                @foreach($product->specifications as $key => $value)
                                    <tr class="border-b border-slate-900 hover:bg-slate-900/20 transition-colors">
                                        <td class="px-6 py-4 font-bold text-slate-400 w-1/3 bg-slate-900/10">{{ $key }}</td>
                                        <td class="px-6 py-4 text-slate-200">{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-slate-500">Tidak ada spesifikasi khusus untuk unit perlengkapan ini.</p>
                @endif
            </div>

            <!-- Tab Content: Description -->
            <div x-show="activeTab === 'desc'" class="max-w-3xl space-y-4 text-sm text-slate-300 leading-relaxed" style="display: none;">
                <p>{{ $product->description }}</p>
                <p class="text-xs text-slate-500 mt-4">
                    * Catatan: Semua bodi kamera disewa lengkap dengan baterai terisi penuh, pengisi daya baterai, tali kamera, dan tas kamera. Perlengkapan camping outdoor dibersihkan, dicuci steril, dan dicek kelengkapan tiang serta pasaknya.
                </p>
            </div>
        </div>

        <!-- Product Reviews (reviews) -->
        <section id="reviews" class="mt-20 border-t border-slate-900 pt-12">
            <h3 class="font-display font-extrabold text-2xl text-white mb-8">Ulasan Pelanggan</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Summary stat -->
                <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-900 h-fit text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-widest font-bold mb-2">Rata-rata Rating</p>
                    <h2 class="font-display font-extrabold text-5xl text-emerald-400 mb-2">{{ $product->rating }}</h2>
                    <div class="flex items-center justify-center text-amber-500 gap-0.5 mb-4">
                        <i data-lucide="star" class="h-5 w-5 fill-amber-500 text-amber-500"></i>
                        <span class="text-sm font-bold text-slate-300 ml-1">Out of 5.0</span>
                    </div>
                    <p class="text-xs text-slate-500">Berdasarkan total {{ $product->reviews()->count() }} ulasan sewa</p>
                </div>

                <!-- Reviews lists -->
                <div class="lg:col-span-2 space-y-6">
                    @forelse($product->reviews as $review)
                        <div class="p-6 rounded-2xl bg-slate-900/20 border border-slate-900">
                            <div class="flex justify-between items-start gap-4 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-emerald-500/10 text-emerald-400 font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($review->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white">{{ $review->user->name }}</h4>
                                        <p class="text-[10px] text-slate-500 font-medium">{{ $review->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex text-amber-500 gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" class="h-3.5 w-3.5 {{ $i <= $review->rating ? 'fill-amber-500 text-amber-500' : 'text-slate-800' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed italic">
                                "{{ $review->comment }}"
                            </p>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-500 border border-dashed border-slate-800 rounded-2xl">
                            <p class="text-sm">Belum ada ulasan untuk perlengkapan sewa ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Related Products Recommendation Section -->
        <section class="mt-20 border-t border-slate-900 pt-12">
            <h3 class="font-display font-extrabold text-2xl text-white mb-8">Rekomendasi Lainnya</h3>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="group rounded-xl bg-slate-900/30 border border-slate-900 hover:border-slate-800 overflow-hidden flex flex-col justify-between hover-glow-emerald bg-gradient-to-b from-slate-900/40 to-slate-950">
                        <a href="{{ route('catalog.show', $rel->slug) }}" class="block aspect-[4/3] w-full bg-slate-950 border-b border-slate-900 relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                <i data-lucide="{{ $rel->category->type === 'camera' ? 'camera' : 'tent' }}" class="h-8 w-8 text-slate-700 group-hover:scale-110 group-hover:text-emerald-500 transition-all duration-300"></i>
                            </div>
                        </a>
                        <div class="p-4">
                            <a href="{{ route('catalog.show', $rel->slug) }}">
                                <h4 class="font-bold text-xs text-white hover:text-emerald-400 line-clamp-1 mb-1">{{ $rel->name }}</h4>
                            </a>
                            <div class="flex items-center justify-between mt-2.5">
                                <span class="text-xs font-extrabold text-emerald-400">Rp {{ number_format($rel->price_per_day, 0, ',', '.') }}</span>
                                <span class="text-[9px] text-slate-500">/hari</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </div>
</section>

<!-- Flatpickr Initialization and Realtime pricing script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pricePerDay = {{ $product->price_per_day }};
        const blockedDates = {!! json_encode($blockedDates) !!};
        
        let startFlat = null;
        let endFlat = null;

        // Custom config for Flatpickr in indonesian standard
        const flatpickrConfig = {
            minDate: "today",
            disable: blockedDates,
            enableTime: true,
            time_24hr: true,
            minuteIncrement: 30,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "d M Y H:i",
            locale: {
                firstDayOfWeek: 1
            },
            onChange: function() {
                calculateCost();
            }
        };

        // Initialize Start date picker
        startFlat = flatpickr("#start_date", {
            ...flatpickrConfig,
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    // Set min date of end date picker to selected start date
                    endFlat.set('minDate', selectedDates[0]);
                }
                calculateCost();
            }
        });

        // Initialize End date picker
        endFlat = flatpickr("#end_date", flatpickrConfig);

        function calculateCost() {
            const startVal = startFlat?.selectedDates?.[0] || null;
            const endVal = endFlat?.selectedDates?.[0] || null;
            const calcBox = document.getElementById('cost-calc-box');
            const calcDuration = document.getElementById('calc-duration');
            const calcTotal = document.getElementById('calc-total');

            if (startVal && endVal) {
                const startDate = new Date(startVal);
                const endDate = new Date(endVal);

                // Calculate total days (inclusive of start & end day)
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                if (!isNaN(diffDays) && diffDays > 0) {
                    const totalCost = diffDays * pricePerDay;

                    // Update UI text
                    calcDuration.textContent = diffDays + ' Hari';
                    calcTotal.textContent = 'Rp ' + totalCost.toLocaleString('id-ID');

                    // Show Box
                    calcBox.classList.remove('hidden');
                }
            } else {
                calcBox.classList.add('hidden');
            }
        }
    });
</script>
@endsection
