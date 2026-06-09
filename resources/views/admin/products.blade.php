@extends('layouts.admin')

@section('page_title', 'Kelola Produk Inventaris')

@section('content')
<div x-data="{ openAddPanel: false, editingProduct: null }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display font-extrabold text-2xl text-white">Inventaris Unit Alat</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar perlengkapan kamera dan camping yang dapat disewa oleh pelanggan.</p>
        </div>
        
        <button @click="openAddPanel = !openAddPanel" class="px-5 py-3 rounded-xl bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald transition-all text-xs flex items-center gap-2">
            <template x-if="!openAddPanel">
                <span class="flex items-center gap-1.5"><i data-lucide="plus" class="h-4 w-4"></i> Tambah Produk</span>
            </template>
            <template x-if="openAddPanel">
                <span class="flex items-center gap-1.5"><i data-lucide="chevron-up" class="h-4 w-4"></i> Tutup Formulir</span>
            </template>
        </button>
    </div>

    <!-- 1. Add Product Collapsible Panel -->
    <div x-show="openAddPanel" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="p-8 rounded-3xl bg-slate-900 border border-slate-800 glass mb-8 shadow-xl"
         style="display: none;">
        
        <h3 class="font-display font-bold text-white text-base border-b border-slate-850 pb-3 mb-6 flex items-center gap-2">
            <i data-lucide="plus" class="text-emerald-400 h-5 w-5"></i> Input Produk Sewa Baru
        </h3>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Perlengkapan</label>
                    <input type="text" id="name" name="name" required placeholder="Sony Alpha 7 IV" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                </div>
                <!-- Category -->
                <div>
                    <label for="category_id" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                    <select id="category_id" name="category_id" required class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->type === 'camera' ? 'Kamera' : 'Camping' }})</option>
                        @endforeach
                    </select>
                </div>
                <!-- Stock -->
                <div>
                    <label for="stock" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Stok Unit</label>
                    <input type="number" id="stock" name="stock" required value="5" min="1"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                </div>
                <!-- Price -->
                <div>
                    <label for="price_per_day" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Harga Sewa / Hari (IDR)</label>
                    <input type="number" id="price_per_day" name="price_per_day" required placeholder="350000" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Denda -->
                <div>
                    <label for="denda_per_day" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Denda Keterlambatan / Hari (IDR)</label>
                    <input type="number" id="denda_per_day" name="denda_per_day" required placeholder="50000" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                </div>
                <!-- Status -->
                <div>
                    <label for="status" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Status Inventaris</label>
                    <select id="status" name="status" required class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                        <option value="available">Tersedia untuk Disewa</option>
                        <option value="unavailable">Sedang Tidak Tersedia</option>
                        <option value="maintenance">Dalam Perbaikan (Maintenance)</option>
                    </select>
                </div>
                <!-- Thumbnail -->
                <div>
                    <label for="thumbnail" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Foto Thumbnail Utama</label>
                    <input type="file" id="thumbnail" name="thumbnail" required class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl p-2.5 text-xs text-white focus:outline-none">
                </div>
                <!-- Gallery multi-image -->
                <div>
                    <label for="gallery" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Foto Galeri Pendukung (Bisa Multi-file)</label>
                    <input type="file" id="gallery" name="gallery[]" multiple class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl p-2.5 text-xs text-white focus:outline-none">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Produk Lengkap</label>
                <textarea id="description" name="description" rows="4" required placeholder="Tulis rincian unit alat, kegunaan, keunggulan, dsb..." 
                          class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none resize-none"></textarea>
            </div>

            <!-- Dynamic Specifications Row-Builder (Alpine.js) -->
            <div x-data="{ specs: [{ key: '', value: '' }] }">
                <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-3">Spesifikasi Teknis Alat</label>
                
                <div class="space-y-3 mb-4">
                    <template x-for="(spec, index) in specs" :key="index">
                        <div class="flex items-center gap-3">
                            <input type="text" x-model="spec.key" name="spec_keys[]" placeholder="Nama Spesifikasi (cth: Sensor)" 
                                   class="w-1/3 bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none">
                            <input type="text" x-model="spec.value" name="spec_values[]" placeholder="Detail Nilai (cth: 33MP Full-Frame)" 
                                   class="w-2/3 bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none">
                            
                            <!-- Remove spec row -->
                            <button type="button" @click="if (specs.length > 1) specs.splice(index, 1)" 
                                    class="p-2.5 rounded-xl bg-slate-950 hover:bg-red-950/40 text-slate-500 hover:text-red-400 border border-slate-800 hover:border-red-900/20 transition-all">
                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <button type="button" @click="specs.push({ key: '', value: '' })" 
                        class="px-4 py-2.5 rounded-xl border border-slate-850 hover:border-emerald-500 text-slate-400 hover:text-emerald-400 hover:bg-slate-950 transition-all font-bold flex items-center gap-1.5">
                    <i data-lucide="plus-circle" class="h-4 w-4"></i> Tambah Baris Spesifikasi
                </button>
            </div>

            <!-- Submit Add Form -->
            <div class="pt-6 border-t border-slate-850 flex justify-end gap-3">
                <button type="button" @click="openAddPanel = false" class="px-5 py-3 rounded-xl border border-slate-850 hover:bg-slate-950 font-bold transition-all">Batal</button>
                <button type="submit" class="px-5 py-3 bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald rounded-xl transition-all shadow-md">Simpan Produk</button>
            </div>
        </form>
    </div>

    <!-- 2. Products List Grid Table -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
        <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-4 mb-6 flex items-center gap-2">
            <i data-lucide="box" class="text-slate-400 h-4.5 w-4.5"></i> Inventaris Unit Terdaftar
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="text-slate-500 uppercase font-semibold border-b border-slate-850 pb-3">
                        <th class="pb-3 w-16">Foto</th>
                        <th class="pb-3">Nama Alat</th>
                        <th class="pb-3">Kategori</th>
                        <th class="pb-3">Stok</th>
                        <th class="pb-3">Sewa / Hari</th>
                        <th class="pb-3">Denda / Hari</th>
                        <th class="pb-3">Rating</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($products as $prod)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <!-- Thumbnail -->
                            <td class="py-4">
                                <div class="h-10 w-12 rounded bg-slate-950 border border-slate-850 flex items-center justify-center text-slate-700 overflow-hidden relative">
                                    <i data-lucide="{{ $prod->category->type === 'camera' ? 'camera' : 'tent' }}" class="h-4 w-4"></i>
                                </div>
                            </td>
                            <!-- Name -->
                            <td class="py-4 font-bold text-white">
                                <a href="{{ route('catalog.show', $prod->slug) }}" target="_blank" class="hover:text-emerald-400 transition-colors">{{ $prod->name }}</a>
                            </td>
                            <!-- Category -->
                            <td class="py-4 text-slate-400">{{ $prod->category->name }}</td>
                            <!-- Stock -->
                            <td class="py-4 font-semibold text-slate-300">{{ $prod->stock }}</td>
                            <!-- Price -->
                            <td class="py-4 font-bold text-emerald-400">Rp {{ number_format($prod->price_per_day, 0, ',', '.') }}</td>
                            <!-- Denda -->
                            <td class="py-4 font-semibold text-red-400">Rp {{ number_format($prod->denda_per_day, 0, ',', '.') }}</td>
                            <!-- Rating -->
                            <td class="py-4 font-semibold text-amber-500">
                                <span class="flex items-center gap-1"><i data-lucide="star" class="h-3 w-3 fill-amber-500"></i> {{ $prod->rating }}</span>
                            </td>
                            <!-- Status -->
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase
                                    @if($prod->status === 'available') bg-emerald-950 text-emerald-400 border border-emerald-500/20
                                    @elseif($prod->status === 'unavailable') bg-slate-950 text-slate-500 border border-slate-800
                                    @else bg-orange-950 text-orange-400 border border-orange-500/20 @endif">
                                    {{ $prod->status === 'available' ? 'Tersedia' : ($prod->status === 'unavailable' ? 'Disewa' : 'Maint.') }}
                                </span>
                            </td>
                            <!-- Actions -->
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end gap-2" x-data="{
                                    specsData: {!! json_encode($prod->specifications ?? []) !!}
                                 }">
                                    
                                    <!-- Edit Trigger Modal (Triggers Alpine state) -->
                                    <button @click="editingProduct = {{ $prod->id }}" class="p-1 rounded bg-slate-950 border border-slate-850 hover:border-emerald-500 text-slate-400 hover:text-emerald-400 transition-colors" title="Edit Data">
                                        <i data-lucide="edit-3" class="h-3.5 w-3.5"></i>
                                    </button>

                                    <!-- Delete Product -->
                                    <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari inventaris?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded bg-slate-950 border border-slate-850 hover:border-red-900/30 hover:bg-red-950/40 text-slate-400 hover:text-red-400 transition-colors" title="Hapus Produk">
                                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                        <!-- 3. Edit Product Panel (Rendered dynamically below table row when clicked!) -->
                        <tr x-show="editingProduct === {{ $prod->id }}" style="display: none;" class="bg-slate-950">
                            <td colspan="9" class="px-8 py-8 border-y border-slate-800">
                                <div class="max-w-4xl space-y-6">
                                    <h4 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-3 flex items-center gap-2">
                                        <i data-lucide="edit-3" class="text-emerald-400 h-4.5 w-4.5"></i> Edit Perlengkapan: {{ $prod->name }}
                                    </h4>

                                    <form action="{{ route('admin.products.update', $prod->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
                                        @csrf
                                        @method('PUT')

                                        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                                            <div class="md:col-span-2">
                                                <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Perlengkapan</label>
                                                <input type="text" name="name" required value="{{ $prod->name }}" class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                                                <select name="category_id" required class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}" {{ $prod->category_id === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Stok Unit</label>
                                                <input type="number" name="stock" required value="{{ $prod->stock }}" min="0" class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Harga Sewa / Hari (IDR)</label>
                                                <input type="number" name="price_per_day" required value="{{ (int)$prod->price_per_day }}" class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                            <div>
                                                <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Denda / Hari (IDR)</label>
                                                <input type="number" name="denda_per_day" required value="{{ (int)$prod->denda_per_day }}" class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Status Inventaris</label>
                                                <select name="status" required class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                                                    <option value="available" {{ $prod->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                                                    <option value="unavailable" {{ $prod->status === 'unavailable' ? 'selected' : '' }}>Disewa</option>
                                                    <option value="maintenance" {{ $prod->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Foto Thumbnail Baru (Opsional)</label>
                                                <input type="file" name="thumbnail" class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl p-2.5 text-xs text-white focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Foto Tambahan Baru (Opsional)</label>
                                                <input type="file" name="gallery[]" multiple class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl p-2.5 text-xs text-white focus:outline-none">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Produk Lengkap</label>
                                            <textarea name="description" rows="4" required class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none resize-none">{{ $prod->description }}</textarea>
                                        </div>

                                        <!-- Specifications Editor -->
                                        <div x-data="{ 
                                            specs: [
                                                @if(!empty($prod->specifications))
                                                    @foreach($prod->specifications as $k => $v)
                                                        { key: '{{ $k }}', value: '{{ $v }}' },
                                                    @endforeach
                                                @else
                                                    { key: '', value: '' }
                                                @endif
                                            ]
                                         }">
                                            <label class="block font-semibold text-slate-400 uppercase tracking-wider mb-3">Edit Spesifikasi Teknis</label>
                                            <div class="space-y-3 mb-4">
                                                <template x-for="(spec, index) in specs" :key="index">
                                                    <div class="flex items-center gap-3">
                                                        <input type="text" x-model="spec.key" name="spec_keys[]" placeholder="Spesifikasi (cth: Sensor)" class="w-1/3 bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none">
                                                        <input type="text" x-model="spec.value" name="spec_values[]" placeholder="Detail (cth: 33MP)" class="w-2/3 bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none">
                                                        <button type="button" @click="if (specs.length > 1) specs.splice(index, 1)" class="p-2.5 rounded-xl bg-slate-900 text-slate-500 hover:text-red-400 border border-slate-800 transition-all">
                                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                            <button type="button" @click="specs.push({ key: '', value: '' })" class="px-4 py-2.5 rounded-xl border border-slate-800 hover:border-emerald-500 text-slate-400 hover:text-emerald-400 font-bold flex items-center gap-1.5">
                                                <i data-lucide="plus-circle" class="h-4 w-4"></i> Tambah Baris
                                            </button>
                                        </div>

                                        <div class="pt-6 border-t border-slate-850 flex justify-end gap-3">
                                            <button type="button" @click="editingProduct = null" class="px-5 py-3 rounded-xl border border-slate-850 hover:bg-slate-900 font-bold transition-all">Batal</button>
                                            <button type="submit" class="px-5 py-3 bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald rounded-xl transition-all shadow-md">Simpan Perubahan</button>
                                        </div>

                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-slate-500">Belum ada unit perlengkapan terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Elegant Pagination links -->
        @if($products->hasPages())
            <div class="pt-6 border-t border-slate-850">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
