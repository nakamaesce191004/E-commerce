@extends('layouts.admin')

@section('page_title', 'Kelola Pengguna Customer')

@section('content')
<div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 glass">
    
    <div class="border-b border-slate-850 pb-6 mb-6">
        <h2 class="font-display font-extrabold text-lg text-white">Daftar Customer Terdaftar</h2>
        <p class="text-xs text-slate-500 mt-1">Manajemen profil akun pengguna yang terdaftar sebagai customer penyewa di platform Anda.</p>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left border-collapse">
            <thead>
                <tr class="text-slate-500 uppercase font-semibold border-b border-slate-850 pb-3">
                    <th class="pb-3 w-12 text-center">ID</th>
                    <th class="pb-3">Nama Customer</th>
                    <th class="pb-3">Nomor Kontak / WA</th>
                    <th class="pb-3 text-center">Frekuensi Sewa</th>
                    <th class="pb-3">Tanggal Bergabung</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-850">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-900/10 transition-colors">
                        <!-- ID -->
                        <td class="py-4 text-center font-semibold text-slate-500">{{ $user->id }}</td>
                        
                        <!-- Name & Email -->
                        <td class="py-4">
                            <p class="font-bold text-white">{{ $user->name }}</p>
                            <p class="text-[10px] text-slate-500 mt-0.5 font-mono">{{ $user->email }}</p>
                        </td>

                        <!-- Phone -->
                        <td class="py-4 font-mono text-slate-300">{{ $user->phone ?? '-' }}</td>

                        <!-- Rental Count -->
                        <td class="py-4 text-center">
                            <span class="px-2 py-0.5 rounded font-bold bg-slate-950 border border-slate-850 text-slate-300">
                                {{ $user->rentals_count }} Kali Sewa
                            </span>
                        </td>

                        <!-- Registered At -->
                        <td class="py-4 text-slate-400">{{ $user->created_at->format('d M Y, H:i') }} WIB</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-500">Belum ada akun customer yang mendaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    @if($users->hasPages())
        <div class="pt-6 border-t border-slate-850">
            {{ $users->links() }}
        </div>
    @endif

</div>
@endsection
