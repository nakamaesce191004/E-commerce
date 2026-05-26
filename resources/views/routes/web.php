<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Storefront Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Customers)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Breeze Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wishlist Toggles
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Booking Checkout & Midtrans Simulator
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{rental}', [CheckoutController::class, 'showPayment'])->name('checkout.payment');
    Route::post('/checkout/payment/{rental}/simulate', [CheckoutController::class, 'simulatePayment'])->name('checkout.simulate');

    // Customer Personal Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/rental/{id}', [UserDashboardController::class, 'show'])->name('dashboard.rental');
    Route::post('/dashboard/rental/{id}/upload-proof', [UserDashboardController::class, 'uploadProof'])->name('dashboard.upload-proof');
    Route::post('/dashboard/review', [UserDashboardController::class, 'storeReview'])->name('dashboard.review');
});

/*
|--------------------------------------------------------------------------
| Administrator Routes (Role Guarded inside Controller constructor)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Admin Categories CRUD
    Route::get('/categories', [AdminDashboardController::class, 'categoriesIndex'])->name('categories.index');
    Route::post('/categories', [AdminDashboardController::class, 'categoriesStore'])->name('categories.store');
    Route::put('/categories/{id}', [AdminDashboardController::class, 'categoriesUpdate'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminDashboardController::class, 'categoriesDestroy'])->name('categories.destroy');

    // Admin Products CRUD
    Route::get('/products', [AdminDashboardController::class, 'productsIndex'])->name('products.index');
    Route::post('/products', [AdminDashboardController::class, 'productsStore'])->name('products.store');
    Route::put('/products/{id}', [AdminDashboardController::class, 'productsUpdate'])->name('products.update');
    Route::delete('/products/{id}', [AdminDashboardController::class, 'productsDestroy'])->name('products.destroy');

    // Admin Rental Orders
    Route::get('/rentals', [AdminDashboardController::class, 'rentalsIndex'])->name('rentals.index');
    Route::get('/rentals/{id}', [AdminDashboardController::class, 'rentalsShow'])->name('rentals.show');
    Route::put('/rentals/{id}', [AdminDashboardController::class, 'rentalsUpdateStatus'])->name('rentals.update');
    Route::get('/rentals/{id}/invoice', [AdminDashboardController::class, 'rentalsInvoice'])->name('rentals.invoice');

    // Financial Reports & User Manager
    Route::get('/reports', [AdminDashboardController::class, 'reportsIndex'])->name('reports.index');
    Route::get('/users', [AdminDashboardController::class, 'usersIndex'])->name('users.index');
});

// Import Breeze Auth definitions
require __DIR__.'/auth.php';
