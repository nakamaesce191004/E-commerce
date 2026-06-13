<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\RentalItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display the catalog with filters.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by Search Text
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by Category Slug
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by Availability
        if ($request->has('status') && $request->status === 'available') {
            $query->where('status', 'available');
        }

        // Filter by Price Range
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price_per_day', '>=', $request->min_price);
        }
        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'popular');
        if ($sortBy === 'price_low') {
            $query->orderBy('price_per_day', 'asc');
        } elseif ($sortBy === 'price_high') {
            $query->orderBy('price_per_day', 'desc');
        } else {
            $query->orderBy('rating', 'desc'); // default popular
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('catalog.index', compact('products', 'categories'));
    }

    /**
     * Display details of a specific product.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'reviews.user'])->where('slug', $slug)->firstOrFail();
        
        // Find related products in the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // Block only dates where all stock is already reserved.
        $activeRentalItems = RentalItem::where('product_id', $product->id)
            ->whereHas('rental', function($q) {
                $q->whereIn('status', ['pending', 'approved', 'borrowed']);
            })
            ->get();

        $reservedByDate = [];
        foreach ($activeRentalItems as $item) {
            $start = Carbon::parse($item->rental->start_date);
            $end = Carbon::parse($item->rental->end_date);

            $period = CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $dateKey = $date->format('Y-m-d');
                $reservedByDate[$dateKey] = ($reservedByDate[$dateKey] ?? 0) + $item->quantity;
            }
        }

        $blockedDates = array_keys(array_filter(
            $reservedByDate,
            fn ($reservedQuantity) => $reservedQuantity >= $product->stock
        ));

        return view('catalog.show', compact('product', 'relatedProducts', 'blockedDates'));
    }
}
