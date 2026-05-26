@extends('layouts.frontend')

@section('title', 'Wishlist Saya - Perlengkapan Favorit')

@section('content')
<section class="py-12 bg-slate-950 min-h-[75vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Headers -->
        <div class="border-b border-slate-900 pb-6 mb-10">
            <h1 class="font-display font-extrabold text-3xl text-white flex items-center gap-3">
                <i data-lucide="heart" class="text-red-500 fill-red-500/20"></i> Wishlist Saya
            </h1>
            <p class="text-xs text-slate-500 mt-2">Daftar perlengkapan sewa kamera dan camping yang Anda sukai atau rencanakan untuk disewa di kemudian hari.</p>
        </div>

        @if(!empty($wishlists) && $wishlists->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($wishlists as $wish)
                    @php $product = $wish->product; @endphp
                    <!-- Product Card -->
                    <div class="group rounded-2xl bg-slate-900/30 border border-slate-900 hover:border-slate-800 transition-all flex flex-col justify-between overflow-hidden relative hover-glow-emerald bg-gradient-to-b from-slate-900/50 to-slate-950">
                        
                        <!-- Thumbnail -->
                        <a href="{{ route('catalog.show', $product->slug) }}" class="block aspect-[4/3] w-full overflow-hidden bg-slate-950 border-b border-slate-900 relative">
                            @if($product->thumbnail_url)
                                <img src="{{ asset($product->thumbnail_url) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                    <i data-lucide="{{ $product->category->type === 'camera' ? 'camera' : 'tent' }}" class="h-10 w-10 text-slate-700 group-hover:scale-110 group-hover:text-emerald-500 transition-all duration-300"></i>
                                </div>
                            @endif
                        </a>

                        <!-- Details -->
                        <div class="p-5 flex-grow flex flex-col justify-between">
                            <div>
                                <!-- Ratings -->
                                <div class="flex items-center gap-1.5 mb-2">
                                    <div class="flex items-center text-amber-500">
                                        <i data-lucide="star" class="h-3.5 w-3.5 fill-amber-500"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-300">{{ $product->rating }}</span>
                                </div>

                                <a href="{{ route('catalog.show', $product->slug) }}" class="block">
                                    <h3 class="font-display font-bold text-sm text-white hover:text-emerald-400 transition-colors line-clamp-1 mb-2">
                                        {{ $product->name }}
                                    </h3>
                                </a>
                                <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-4">
                                    {{ $product->description }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-900 pt-4 mt-auto">
                                <div>
                                    <p class="text-[9px] text-slate-500 uppercase font-semibold">Harga Sewa</p>
                                    <div class="flex items-baseline gap-0.5">
                                        <span class="text-xs font-extrabold text-emerald-400">Rp {{ number_format($product->price_per_day, 0, ',', '.') }}</span>
                                        <span class="text-[9px] text-slate-500">/hari</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <!-- Delete from wishlist -->
                                    <form action="{{ route('wishlist.toggle') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="p-2 rounded-xl bg-slate-950 text-slate-400 hover:text-red-500 border border-slate-800 hover:border-red-900/30 transition-all" title="Hapus dari Wishlist">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('catalog.show', $product->slug) }}" class="p-2 rounded-xl bg-emerald-500 text-slate-950 hover:bg-emerald-400 hover-glow-emerald transition-all" title="Sewa Sekarang">
                                        <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty state -->
            <div class="max-w-md mx-auto text-center py-20 bg-slate-900/10 border border-slate-900 rounded-3xl p-8 glass">
                <div class="h-16 w-16 bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-600 border border-slate-800">
                    <i data-lucide="heart" class="h-8 w-8 text-slate-600"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-white mb-2">Wishlist Kosong</h3>
                <p class="text-xs text-slate-400 mb-8 leading-relaxed">Anda belum menambahkan perlengkapan apa pun ke daftar favorit Anda. Jelajahi katalog dan klik tombol hati pada perlengkapan!</p>
                <a href="{{ route('catalog.index') }}" class="px-6 py-3.5 rounded-xl bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald transition-all text-xs inline-flex items-center gap-2">
                    Cari Perlengkapan <i data-lucide="binoculars" class="h-4.5 w-4.5"></i>
                </a>
            </div>
        @endif

    </div>
</section>
@endsection
