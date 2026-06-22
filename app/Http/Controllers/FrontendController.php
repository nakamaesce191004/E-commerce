<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * Display the homepage.
     */
    public function home()
    {
        // Get popular categories
        $categories = Category::all()->take(6);

        // Get featured products (high rating & available)
        $featuredProducts = Product::with('category')
            ->where('status', 'available')
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        // Get some newly arrived products
        $latestProducts = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('home', compact('categories', 'featuredProducts', 'latestProducts'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('contact');
    }
}