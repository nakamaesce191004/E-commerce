@extends('layouts.frontend')

@section('title', 'Gerbang Pembayaran Aman - Midtrans Simulator')

@section('content')
<section class="checkout-payment-page py-16 bg-slate-950 min-h-[80vh] flex items-center justify-center relative overflow-hidden">
    
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-emerald-950/15 via-slate-950 to-slate-950"></div>

    <div class="max-w-md w-full mx-auto px-4 relative z-10" x-data="{ 
            selectedMethod: 'qris', 
            simulating: false,
            success: false,
            simulate(status) {
                this.simulating = true;
                
                // Call simulator API
                fetch('{{ route('checkout.simulate', $rental->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (status === 'settled') {
                            this.success = true;
                            setTimeout(() => {
                                window.location.href = '{{ route('dashboard') }}';
                            }, 2000);
                        } else {
                            window.location.href = '{{ route('dashboard') }}';
                        }
                    } else {
                        alert('Gagal mensimulasikan pembayaran.');
                        this.simulating = false;
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan.');
                    this.simulating = false;
                });
            }
         }">
        
        <!-- Main Simulator Frame -->
        <div class="rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl overflow-hidden glass p-6 space-y-6 relative"
             x-show="!success"
             x-transition:leave="transition ease-in duration-300">
            
            <!-- Midtrans mock Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-md bg-emerald-500 text-slate-950 text-[10px] font-extrabold tracking-wider font-mono uppercase">SECURE</span>
                    <span class="text-xs font-bold text-slate-400">Midtrans Mock Sandbox</span>
                </div>
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></div>
            </div>

            <!-- Merch details -->
            <div class="text-center py-4 bg-slate-950/60 rounded-2xl border border-slate-950">
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">Membayar Ke EquipRent</p>
                <h2 class="font-display font-extrabold text-2xl text-emerald-400">
                    Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                </h2>
                <p class="text-[10px] text-slate-400 mt-1 font-mono">Invoice Ref: #{{ $rental->payment->transaction_id ?? $rental->id }}</p>
            </div>

            <!-- Select mockup channels -->
            <div class="space-y-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Simulasi Saluran Pembayaran</p>
                
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button @click="selectedMethod = 'qris'" 
                            class="p-3 rounded-xl border text-left flex items-center gap-2 hover:bg-slate-800/40 transition-all focus:outline-none"
                            :class="selectedMethod === 'qris' ? 'border-emerald-500 bg-emerald-500/5 text-white font-bold' : 'border-slate-800 text-slate-400'">
                        <i data-lucide="qr-code" class="h-4 w-4 text-emerald-400"></i> QRIS (Gopay/OVO)
                    </button>
                    <button @click="selectedMethod = 'bca'" 
                            class="p-3 rounded-xl border text-left flex items-center gap-2 hover:bg-slate-800/40 transition-all focus:outline-none"
                            :class="selectedMethod === 'bca' ? 'border-emerald-500 bg-emerald-500/5 text-white font-bold' : 'border-slate-800 text-slate-400'">
                        <i data-lucide="landmark" class="h-4 w-4 text-emerald-400"></i> BCA Virtual Account
                    </button>
                    <button @click="selectedMethod = 'mandiri'" 
                            class="p-3 rounded-xl border text-left flex items-center gap-2 hover:bg-slate-800/40 transition-all focus:outline-none"
                            :class="selectedMethod === 'mandiri' ? 'border-emerald-500 bg-emerald-500/5 text-white font-bold' : 'border-slate-800 text-slate-400'">
                        <i data-lucide="building" class="h-4 w-4 text-emerald-400"></i> Mandiri VA
                    </button>
                    <button @click="selectedMethod = 'cc'" 
                            class="p-3 rounded-xl border text-left flex items-center gap-2 hover:bg-slate-800/40 transition-all focus:outline-none"
                            :class="selectedMethod === 'cc' ? 'border-emerald-500 bg-emerald-500/5 text-white font-bold' : 'border-slate-800 text-slate-400'">
                        <i data-lucide="credit-card" class="h-4 w-4 text-emerald-400"></i> Kartu Kredit
                    </button>
                </div>
            </div>

            <!-- Action buttons simulation -->
            <div class="pt-4 border-t border-slate-800 space-y-3">
                <template x-if="simulating">
                    <div class="flex items-center justify-center py-4 text-xs text-slate-400 gap-2">
                        <span class="h-4 w-4 rounded-full border-2 border-emerald-500 border-t-transparent animate-spin"></span>
                        Memproses simulasi transaksi...
                    </div>
                </template>

                <template x-if="!simulating">
                    <div class="space-y-2">
                        <button @click="simulate('settled')" 
                                class="w-full py-3.5 rounded-xl font-bold bg-emerald-500 text-slate-950 hover:bg-emerald-400 hover-glow-emerald transition-all text-xs flex items-center justify-center gap-1.5 shadow-lg shadow-emerald-500/10">
                            <i data-lucide="check-circle" class="h-4.5 w-4.5"></i> Simulasikan Bayar Sukses
                        </button>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <button @click="simulate('expired')" 
                                    class="py-3 rounded-xl font-semibold bg-red-950/40 text-red-400 border border-red-900/20 hover:bg-red-900/20 text-xs flex items-center justify-center gap-1">
                                <i data-lucide="x-circle" class="h-3.5 w-3.5"></i> Simulasikan Gagal
                            </button>
                            <a href="{{ route('dashboard') }}" 
                               class="py-3 rounded-xl font-semibold bg-slate-950 border border-slate-800 text-slate-400 hover:text-white text-xs flex items-center justify-center gap-1 text-center">
                                <i data-lucide="clock" class="h-3.5 w-3.5"></i> Bayar Nanti
                            </a>
                        </div>
                    </div>
                </template>
            </div>

        </div>

        <!-- Success overlay page -->
        <div class="rounded-3xl bg-slate-900 border border-slate-800 p-8 glass text-center space-y-6 shadow-2xl"
             x-show="success"
             x-transition:enter="transition ease-out duration-300"
             style="display: none;">
            <div class="h-20 w-20 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center justify-center mx-auto scale-110 shadow-lg shadow-emerald-500/10 animate-bounce">
                <i data-lucide="check" class="h-10 w-10"></i>
            </div>
            <h3 class="font-display font-extrabold text-2xl text-white">Pembayaran Sukses!</h3>
            <p class="text-xs text-slate-400 max-w-xs mx-auto leading-relaxed">
                Terima kasih! Pembayaran Anda sebesar <span class="text-white font-bold">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</span> telah terverifikasi secara otomatis.
            </p>
            <div class="text-xs text-slate-500 flex items-center justify-center gap-2">
                <span class="h-3.5 w-3.5 rounded-full border-2 border-emerald-500 border-t-transparent animate-spin"></span>
                Mengarahkan Anda ke Dashboard Saya...
            </div>
        </div>

    </div>
</section>
@endsection
