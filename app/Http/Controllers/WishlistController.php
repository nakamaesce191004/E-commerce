<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display the wishlist page.
     */
    public function index()
    {
        $wishlists = Wishlist::with('product.category')
            ->where('user_id', auth()->id())
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle a product in the wishlist.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = auth()->id();
        $productId = $request->product_id;

        $exists = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($exists) {
            $exists->delete();
            return redirect()->back()->with('success', 'Produk dihapus dari wishlist.');
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        return redirect()->back()->with('success', 'Produk ditambahkan ke wishlist!');
    }
}
