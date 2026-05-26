<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    /**
     * Enforce admin check in constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator.');
            }
            return $next($request);
        });
    }

    /**
     * Admin dashboard home with statistics.
     */
    public function index()
    {
        // 1. Calculate stats
        $totalRevenue = Rental::where('payment_status', 'paid')->sum('total_price');
        $activeRentalsCount = Rental::whereIn('status', ['approved', 'borrowed'])->count();
        $totalProductsCount = Product::count();
        $totalUsersCount = User::where('role', 'customer')->count();

        // 2. Get recent transactions
        $recentTransactions = Rental::with(['user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 3. Get monthly revenue charts data (last 6 months)
        $revenueData = Rental::select(
                DB::raw("strftime('%m', created_at) as month"),
                DB::raw("sum(total_price) as total")
            )
            ->where('payment_status', 'paid')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->take(6)
            ->get();

        $chartLabels = [];
        $chartValues = [];
        
        // Populate chart details
        foreach ($revenueData as $data) {
            $monthNum = (int)$data->month;
            $monthName = Carbon::create()->month($monthNum)->translatedFormat('F');
            $chartLabels[] = $monthName;
            $chartValues[] = (float)$data->total;
        }

        if (empty($chartLabels)) {
            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
            $chartValues = [0, 0, 0, 0, 0, 0];
        }

        // 4. Get popular products
        $popularProducts = Product::with('category')
            ->orderBy('rating', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'activeRentalsCount', 
            'totalProductsCount', 
            'totalUsersCount',
            'recentTransactions',
            'chartLabels',
            'chartValues',
            'popularProducts'
        ));
    }

    /* =========================================================================
       CATEGORIES CRUD
       ========================================================================= */

    public function categoriesIndex()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories', compact('categories'));
    }

    public function categoriesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:camera,camping',
            'icon' => 'required|string|max:50'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'icon' => $request->icon
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function categoriesUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:camera,camping',
            'icon' => 'required|string|max:50'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'icon' => $request->icon
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function categoriesDestroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }

    /* =========================================================================
       PRODUCTS CRUD
       ========================================================================= */

    public function productsIndex()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function productsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_per_day' => 'required|numeric|min:0',
            'description' => 'required|string',
            'status' => 'required|in:available,unavailable,maintenance',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // Upload Thumbnail
        $thumbFile = $request->file('thumbnail');
        $thumbName = 'thumb_' . time() . '_' . rand(100, 999) . '.' . $thumbFile->getClientOriginalExtension();
        $thumbFile->move(public_path('uploads/products'), $thumbName);
        $thumbPath = 'uploads/products/' . $thumbName;

        // Upload Multi-image Gallery
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galName = 'gal_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $galName);
                $galleryPaths[] = 'uploads/products/' . $galName;
            }
        }

        // Specs builder from input
        $specs = [];
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $keys = $request->spec_keys;
            $vals = $request->spec_values;
            for ($i = 0; $i < count($keys); $i++) {
                if (!empty($keys[$i])) {
                    $specs[$keys[$i]] = $vals[$i] ?? '';
                }
            }
        }

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . rand(1000, 9999),
            'category_id' => $request->category_id,
            'price_per_day' => $request->price_per_day,
            'description' => $request->description,
            'status' => $request->status,
            'thumbnail' => $thumbPath,
            'gallery' => $galleryPaths,
            'specifications' => $specs,
            'rating' => 5.0
        ]);

        return redirect()->back()->with('success', 'Produk inventaris baru berhasil ditambahkan!');
    }

    public function productsUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_per_day' => 'required|numeric|min:0',
            'description' => 'required|string',
            'status' => 'required|in:available,unavailable,maintenance',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $thumbPath = $product->thumbnail;
        if ($request->hasFile('thumbnail')) {
            // Delete old file
            if (file_exists(public_path($product->thumbnail)) && is_file(public_path($product->thumbnail))) {
                @unlink(public_path($product->thumbnail));
            }
            // Upload new
            $thumbFile = $request->file('thumbnail');
            $thumbName = 'thumb_' . time() . '_' . rand(100, 999) . '.' . $thumbFile->getClientOriginalExtension();
            $thumbFile->move(public_path('uploads/products'), $thumbName);
            $thumbPath = 'uploads/products/' . $thumbName;
        }

        $galleryPaths = $product->gallery ?? [];
        if ($request->hasFile('gallery')) {
            // Upload new gallery files
            foreach ($request->file('gallery') as $file) {
                $galName = 'gal_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $galName);
                $galleryPaths[] = 'uploads/products/' . $galName;
            }
        }

        // Specs update
        $specs = [];
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $keys = $request->spec_keys;
            $vals = $request->spec_values;
            for ($i = 0; $i < count($keys); $i++) {
                if (!empty($keys[$i])) {
                    $specs[$keys[$i]] = $vals[$i] ?? '';
                }
            }
        } else {
            $specs = $product->specifications;
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price_per_day' => $request->price_per_day,
            'description' => $request->description,
            'status' => $request->status,
            'thumbnail' => $thumbPath,
            'gallery' => $galleryPaths,
            'specifications' => $specs
        ]);

        return redirect()->back()->with('success', 'Data produk sewa berhasil diperbarui!');
    }

    public function productsDestroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete images
        if (file_exists(public_path($product->thumbnail)) && is_file(public_path($product->thumbnail))) {
            @unlink(public_path($product->thumbnail));
        }
        if (!empty($product->gallery)) {
            foreach ($product->gallery as $image) {
                if (file_exists(public_path($image)) && is_file(public_path($image))) {
                    @unlink(public_path($image));
                }
            }
        }

        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari inventaris!');
    }

    /* =========================================================================
       RENTALS / TRANSACTIONS MANAGEMENT
       ========================================================================= */

    public function rentalsIndex(Request $request)
    {
        $query = Rental::with(['user', 'payment']);

        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('id', $request->search);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $rentals = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.rentals', compact('rentals'));
    }

    public function rentalsShow($id)
    {
        $rental = Rental::with(['user', 'items.product', 'payment'])->findOrFail($id);
        return view('admin.rental-detail', compact('rental'));
    }

    public function rentalsUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,borrowed,completed,rejected',
            'payment_status' => 'required|in:unpaid,paid,expired'
        ]);

        $rental = Rental::with('payment')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            $rental->update([
                'status' => $request->status,
                'payment_status' => $request->payment_status
            ]);

            // Sync payment status
            $paymentStatus = 'pending';
            if ($request->payment_status === 'paid') {
                $paymentStatus = 'settled';
            } elseif ($request->payment_status === 'expired') {
                $paymentStatus = 'expired';
            }
            
            $rental->payment->update(['status' => $paymentStatus]);

            DB::commit();
            return redirect()->back()->with('success', 'Status transaksi & sewa berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function rentalsInvoice($id)
    {
        $rental = Rental::with(['user', 'items.product.category', 'payment'])->findOrFail($id);
        return view('admin.invoice', compact('rental'));
    }

    /* =========================================================================
       REPORTS & USER LIST
       ========================================================================= */

    public function reportsIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $rentals = Rental::with(['user', 'payment'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalRevenue = $rentals->sum('total_price');
        $totalOrders = $rentals->count();

        return view('admin.reports', compact('rentals', 'totalRevenue', 'totalOrders', 'startDate', 'endDate'));
    }

    public function usersIndex()
    {
        $users = User::withCount('rentals')
            ->where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users', compact('users'));
    }
}
