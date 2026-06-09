<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('catalog.index')->with('error', 'Keranjang booking Anda kosong. Silakan pilih perlengkapan sewa terlebih dahulu.');
        }

        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += $item['subtotal'];
        }

        $user = auth()->user();

        return view('checkout.index', compact('cart', 'grandTotal', 'user'));
    }

    /**
     * Process checkout submission.
     */
    public function process(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'ktp_name' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'ktp_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('catalog.index')->with('error', 'Keranjang sewa Anda kosong.');
        }

        $user = auth()->user();
        $paymentMethod = 'midtrans';

        // Ensure user phone is updated if empty
        if (empty($user->phone)) {
            $user->update([
                'phone' => $request->phone
            ]);
        }

        // Double check date conflicts one last time to prevent race conditions
        foreach ($cart as $item) {
            $productId = $item['product_id'];
            $startDate = Carbon::parse($item['start_date']);
            $endDate = Carbon::parse($item['end_date']);

            $isClashed = RentalItem::where('product_id', $productId)
                ->whereHas('rental', function($q) use ($startDate, $endDate) {
                    $q->whereIn('status', ['pending', 'approved', 'borrowed'])
                      ->where(function($q2) use ($startDate, $endDate) {
                          $q2->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                            ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                            ->orWhere(function($q3) use ($startDate, $endDate) {
                                $q3->where('start_date', '<=', $startDate->format('Y-m-d'))
                                   ->where('end_date', '>=', $endDate->format('Y-m-d'));
                            });
                      });
                })->exists();

            if ($isClashed) {
                return redirect()->route('cart.index')->with('error', 'Maaf, produk "' . $item['name'] . '" sudah disewa oleh pengguna lain pada tanggal yang Anda pilih. Silakan sesuaikan keranjang Anda.');
            }
        }

        // Start Database Transaction
        DB::beginTransaction();

        try {
            $ktpPath = null;
            if ($request->hasFile('ktp_photo')) {
                $uploadPath = public_path('uploads/ktp');
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $file = $request->file('ktp_photo');
                $filename = 'ktp_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $ktpPath = 'uploads/ktp/' . $filename;
            }

            // Find overall start and end dates
            $startDates = [];
            $endDates = [];
            $grandTotal = 0;

            foreach ($cart as $item) {
                $startDates[] = Carbon::parse($item['start_date']);
                $endDates[] = Carbon::parse($item['end_date']);
                $grandTotal += $item['subtotal'];
            }

            $minStart = min($startDates);
            $maxEnd = max($endDates);
            $totalDays = $minStart->diffInDays($maxEnd) + 1;

            // 1. Create Rental Record
            $rental = Rental::create([
                'user_id' => $user->id,
                'start_date' => $minStart->format('Y-m-d'),
                'end_date' => $maxEnd->format('Y-m-d'),
                'total_days' => $totalDays,
                'total_price' => $grandTotal,
                'status' => 'pending',
                'phone' => $request->phone,
                'ktp_name' => $request->ktp_name,
                'nik' => $request->nik,
                'ktp_photo' => $ktpPath,
                'payment_method' => $paymentMethod,
                'payment_status' => 'unpaid'
            ]);

            // 2. Create Rental Items
            foreach ($cart as $item) {
                RentalItem::create([
                    'rental_id' => $rental->id,
                    'product_id' => $item['product_id'],
                    'price_per_day' => $item['price_per_day'],
                    'quantity' => 1,
                    'subtotal' => $item['subtotal']
                ]);
            }

            // 3. Create initial Payment Record
            $payment = Payment::create([
                'rental_id' => $rental->id,
                'amount' => $grandTotal,
                'payment_type' => $paymentMethod,
                'transaction_id' => 'INV-' . strtoupper(uniqid()),
                'status' => 'pending'
            ]);

            DB::commit();

            // Clear Cart Session
            session()->forget('cart');

            return redirect()->route('checkout.payment', $rental->id)->with('success', 'Order sewa berhasil dibuat. Silakan selesaikan pembayaran melalui gateway.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Show the interactive Midtrans Simulator page.
     */
    public function showPayment($rentalId)
    {
        $rental = Rental::with(['items.product', 'payment'])->where('user_id', auth()->id())->findOrFail($rentalId);
        
        if ($rental->payment_status === 'paid') {
            return redirect()->route('dashboard')->with('success', 'Transaksi ini sudah selesai dibayar!');
        }

        return view('checkout.payment', compact('rental'));
    }

    /**
     * Handle payment mock responses.
     */
    public function simulatePayment(Request $request, $rentalId)
    {
        $request->validate([
            'status' => 'required|in:settled,expired,failed'
        ]);

        $rental = Rental::with('payment')->findOrFail($rentalId);
        $status = $request->status;

        DB::beginTransaction();
        try {
            if ($status === 'settled') {
                $rental->update(['payment_status' => 'paid', 'status' => 'approved']);
                $rental->payment->update(['status' => 'settled', 'raw_payload' => ['simulation' => 'success', 'timestamp' => now()]]);
                
                // Set products to unavailable for rental periods
                // For simplicity, we keep available but log transaction
            } else {
                $rental->update(['payment_status' => 'expired', 'status' => 'rejected']);
                $rental->payment->update(['status' => $status, 'raw_payload' => ['simulation' => $status, 'timestamp' => now()]]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
