<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Review;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard index.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Fetch user rentals ordered by latest
        $rentals = Rental::with(['items.product', 'payment'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Stats calculation
        $totalRentalsCount = $rentals->count();
        $activeRentalsCount = $rentals->whereIn('status', ['approved', 'borrowed'])->count();
        $totalSpent = $rentals->where('payment_status', 'paid')->sum('total_price');

        return view('dashboard', compact('rentals', 'totalRentalsCount', 'activeRentalsCount', 'totalSpent'));
    }

    /**
     * Display detailed information of a rental.
     */
    public function show($id)
    {
        $rental = Rental::with(['items.product.category', 'payment'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // Generate WA confirm text
        $waPhone = '6281234567890'; // Admin WhatsApp phone number
        $itemsText = '';
        foreach ($rental->items as $item) {
            $itemsText .= "- " . $item->product->name . " (Price: Rp " . number_format($item->price_per_day, 0, ',', '.') . "/hari)\n";
        }
        $message = "Halo Admin EquipRent, saya ingin mengkonfirmasi pembayaran sewa alat:\n\n"
                 . "*No Invoice:* #" . ($rental->payment->transaction_id ?? $rental->id) . "\n"
                 . "*Nama Penyewa:* " . auth()->user()->name . "\n"
                 . "*Periode Sewa:* " . $rental->start_date->format('d M Y') . " s/d " . $rental->end_date->format('d M Y') . " (" . $rental->total_days . " Hari)\n"
                 . "*Peralatan yang Disewa:*\n" . $itemsText . "\n"
                 . "*Total Pembayaran:* Rp " . number_format($rental->total_price, 0, ',', '.') . "\n"
                 . "*Metode Pembayaran:* " . strtoupper($rental->payment_method) . "\n\n"
                 . "Mohon untuk segera dikonfirmasi. Terima kasih!";

        $waLink = "https://api.whatsapp.com/send?phone=" . $waPhone . "&text=" . urlencode($message);

        return view('dashboard-detail', compact('rental', 'waLink'));
    }

    /**
     * Upload Bank Transfer proof of payment.
     */
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $rental = Rental::with('payment')->where('user_id', auth()->id())->findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            // Store payment proof file
            $file = $request->file('payment_proof');
            $filename = 'proof_' . $rental->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Standard store in public folder
            $file->move(public_path('uploads/proofs'), $filename);
            $proofPath = 'uploads/proofs/' . $filename;

            // Update database
            $rental->payment->update([
                'payment_proof' => $proofPath,
                'status' => 'pending' // waits for admin approval
            ]);

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah! Admin kami akan segera memverifikasinya.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }

    /**
     * Submit product review and rating.
     */
    public function storeReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5'
        ]);

        $userId = auth()->id();
        $productId = $request->product_id;

        // Ensure user hasn't already reviewed this product
        $exists = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah memberikan review untuk perlengkapan ini.');
        }

        // Create Review
        Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        // Re-calculate Product average rating
        $product = Product::findOrFail($productId);
        $averageRating = Review::where('product_id', $productId)->avg('rating');
        $product->update([
            'rating' => round($averageRating, 1)
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Review Anda berhasil dikirim.');
    }
}
