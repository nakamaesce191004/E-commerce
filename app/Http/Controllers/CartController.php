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
            'start_date' => 'required|date_format:Y-m-d H:i|after_or_equal:now',
            'end_date' => 'required|date_format:Y-m-d H:i|after_or_equal:start_date',
        ]);

        $productId = $request->product_id;
        $startAt = Carbon::parse($request->start_date);
        $endAt = Carbon::parse($request->end_date);
        $startDate = $startAt->copy()->startOfDay();
        $endDate = $endAt->copy()->startOfDay();
        $durationHours = max(0, $startAt->diffInHours($endAt));
        $totalDays = max(1, (int) ceil($durationHours / 24));

        $product = Product::findOrFail($productId);

        if ($product->status !== 'available') {
            return redirect()->back()->with('error', 'Produk ini sedang tidak tersedia untuk disewa saat ini.');
        }

        // --- CHECK DATE CONFLICT / DOUBLE BOOKING WITH STOCK-AWARENESS ---
        $isClashed = false;
        $tempDate = clone $startDate;
        for ($date = $tempDate; $date->lte($endDate); $date->addDay()) {
            $activeBookingsCount = RentalItem::where('product_id', $productId)
                ->whereHas('rental', function($q) use ($date) {
                    $q->whereIn('status', ['pending', 'approved', 'borrowed'])
                      ->where('start_date', '<=', $date->format('Y-m-d'))
                      ->where('end_date', '>=', $date->format('Y-m-d'));
                })
                ->sum('quantity');

            if (($activeBookingsCount + 1) > $product->stock) {
                $isClashed = true;
                break;
            }
        }

        if ($isClashed) {
            return redirect()->back()->with('error', 'Maaf, stok peralatan ini tidak mencukupi pada tanggal tersebut karena sudah disewa oleh pengguna lain. Silakan pilih tanggal lain.');
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
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
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
