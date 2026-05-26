@extends('layouts.admin')

@section('page_title', 'Kelola Kategori Perlengkapan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- Left Column: Categories List Table (8cols) -->
    <div class="lg:col-span-8 p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
        <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-4 mb-6 flex items-center gap-2">
            <i data-lucide="folder-open" class="text-slate-400 h-4.5 w-4.5"></i> Daftar Kategori Aktif
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="text-slate-500 uppercase font-semibold border-b border-slate-850 pb-3">
                        <th class="pb-3 w-12 text-center">ID</th>
                        <th class="pb-3">Kategori</th>
                        <th class="pb-3">Slug</th>
                        <th class="pb-3">Tipe</th>
                        <th class="pb-3">Icon Name</th>
                        <th class="pb-3 text-center">Jumlah Produk</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-900/10 transition-colors" x-data="{ editing: false, name: '{{ $cat->name }}', type: '{{ $cat->type }}', icon: '{{ $cat->icon }}' }">
                            <td class="py-4 text-center font-semibold text-slate-500">{{ $cat->id }}</td>
                            <td class="py-4">
                                <template x-if="!editing">
                                    <span class="font-bold text-white flex items-center gap-2">
                                        <i data-lucide="{{ $cat->icon ?? 'box' }}" class="h-4 w-4 text-emerald-400"></i>
                                        {{ $cat->name }}
                                    </span>
                                </template>
                                <template x-if="editing">
                                    <input type="text" x-model="name" class="bg-slate-950 border border-slate-850 rounded px-2 py-1 text-xs text-white focus:outline-none focus:border-emerald-500">
                                </template>
                            </td>
                            <td class="py-4 font-mono text-slate-400">{{ $cat->slug }}</td>
                            <td class="py-4">
                                <template x-if="!editing">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold border {{ $cat->type === 'camera' ? 'bg-blue-950/40 text-blue-400 border-blue-900/30' : 'bg-green-950/40 text-green-400 border-green-900/30' }}">
                                        {{ $cat->type === 'camera' ? 'Kamera' : 'Camping' }}
                                    </span>
                                </template>
                                <template x-if="editing">
                                    <select x-model="type" class="bg-slate-950 border border-slate-855 text-xs text-white rounded px-2 py-1 focus:outline-none">
                                        <option value="camera">Kamera</option>
                                        <option value="camping">Camping</option>
                                    </select>
                                </template>
                            </td>
                            <td class="py-4 font-mono text-[10px] text-slate-500">
                                <template x-if="!editing">
                                    <span x-text="icon"></span>
                                </template>
                                <template x-if="editing">
                                    <input type="text" x-model="icon" class="bg-slate-950 border border-slate-850 rounded px-2 py-1 text-xs text-white focus:outline-none focus:border-emerald-500 w-24">
                                </template>
                            </td>
                            <td class="py-4 text-center font-bold text-slate-300">{{ $cat->products_count }} Unit</td>
                            <td class="py-4 text-right">
                                <!-- Standard CRUD Actions -->
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- Edit state buttons -->
                                    <template x-if="!editing">
                                        <button @click="editing = true" class="p-1 rounded bg-slate-950 border border-slate-850 hover:border-emerald-500 text-slate-400 hover:text-emerald-400 transition-colors" title="Edit Kategori">
                                            <i data-lucide="edit-3" class="h-3.5 w-3.5"></i>
                                        </button>
                                    </template>

                                    <template x-if="editing">
                                        <div class="flex items-center gap-1">
                                            <!-- Save Form -->
                                            <form :action="'{{ route('admin.categories.update', '') }}/' + {{ $cat->id }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="name" :value="name">
                                                <input type="hidden" name="type" :value="type">
                                                <input type="hidden" name="icon" :value="icon">
                                                <button type="submit" class="p-1 rounded bg-emerald-500 text-slate-950 hover:bg-emerald-400 transition-colors" title="Simpan">
                                                    <i data-lucide="check" class="h-3.5 w-3.5"></i>
                                                </button>
                                            </form>
                                            <button @click="editing = false" class="p-1 rounded bg-slate-950 border border-slate-850 hover:border-red-900/30 text-slate-400 hover:text-red-400" title="Batal">
                                                <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Delete Category -->
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua produk dalam kategori ini juga akan terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded bg-slate-950 border border-slate-850 hover:border-red-900/30 hover:bg-red-950/40 text-slate-400 hover:text-red-400 transition-colors" title="Hapus Kategori">
                                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-slate-500">Belum ada kategori terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Add Category Form Card (4cols) -->
    <div class="lg:col-span-4 p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
        <h3 class="font-display font-bold text-white text-sm border-b border-slate-850 pb-4 mb-6 flex items-center gap-2">
            <i data-lucide="plus-circle" class="text-emerald-400 h-4.5 w-4.5"></i> Tambah Kategori Baru
        </h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5 text-xs">
            @csrf
            
            <div>
                <label for="name" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Kategori</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Kamera Mirrorless" 
                       class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label for="type" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Tipe Perlengkapan</label>
                <select id="type" name="type" required class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none">
                    <option value="camera">Kamera & Aksesoris Foto</option>
                    <option value="camping">Camping & Perlengkapan Outdoor</option>
                </select>
            </div>

            <div>
                <label for="icon" class="block font-semibold text-slate-400 uppercase tracking-wider mb-2">Icon Lucide (Berasal dari Lucide Icons)</label>
                <input type="text" id="icon" name="icon" required placeholder="Contoh: Camera, Tent, Plane, Sun" 
                       class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
                <p class="text-[10px] text-slate-500 mt-1.5 leading-relaxed">
                    Ketik nama icon Lucide resmi (case-sensitive) seperti: `Camera`, `Disc`, `Plane`, `Tent`, `Briefcase`, `Moon`, `Flame`, `Sun`, `Compass`.
                </p>
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-500 text-slate-950 font-bold hover:bg-emerald-400 hover-glow-emerald rounded-xl transition-all shadow-md">
                Tambah Kategori
            </button>
        </form>
    </div>

</div>
@endsection
