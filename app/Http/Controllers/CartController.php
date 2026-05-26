<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RentalItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart index page.
     */
    public function index()
    {
        $cart = session('cart', []);
        
        // Calculate grand total
        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += $item['subtotal'];
        }

        return view('cart.index', compact('cart', 'grandTotal'));
    }

    /**
     * Add a product to the cart with date validations.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $productId = $request->product_id;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1; // inclusive of start/end days

        $product = Product::findOrFail($productId);

        if ($product->status !== 'available') {
            return redirect()->back()->with('error', 'Produk ini sedang tidak tersedia untuk disewa saat ini.');
        }

        // --- CHECK DATE CONFLICT / DOUBLE BOOKING ---
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
            return redirect()->back()->with('error', 'Maaf, peralatan ini sudah disewa pengguna lain pada tanggal tersebut. Silakan pilih tanggal lain.');
        }

        // Setup Cart Item
        $cart = session('cart', []);

        // Put in cart
        $cart[$productId] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'thumbnail' => $product->thumbnail,
            'price_per_day' => $product->price_per_day,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_days' => $totalDays,
            'subtotal' => $product->price_per_day * $totalDays
        ];

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang booking!');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove($id)
    {
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
            return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan.');
    }

    /**
     * Empty the cart.
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang sewa dikosongkan.');
    }
}
