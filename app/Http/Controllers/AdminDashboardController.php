<?php
// app/Http/Controllers/AdminDashboardController.php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Spatie\Browsershot\Browsershot;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 1. Jumlah produk berdasarkan kategori
        $productsByCategory = Product::select('category_id', DB::raw('count(*) as total'))
            ->with('category:id,name')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name,
                    'total' => $item->total,
                ];
            });

        // 2. Jumlah toko berdasarkan provinsi
        $storesByProvince = Store::select('pic_province_name', DB::raw('count(*) as total'))
            ->groupBy('pic_province_name')
            ->orderBy('total', 'desc')
            ->get();

        // 3. Seller aktif dan tidak aktif
        $activeStores = Store::has('products')->count();
        $inactiveStores = Store::doesntHave('products')->count();

        // 4. Jumlah user yang memberikan review (unique)
        $reviewersCount = Review::distinct('user_id')->whereNotNull('user_id')->count('user_id');
        $guestReviewersCount = Review::whereNull('user_id')->distinct('guest_email')->count('guest_email');
        $totalReviewers = $reviewersCount + $guestReviewersCount;

        return response()->json([
            'products_by_category' => $productsByCategory,
            'stores_by_province' => $storesByProvince,
            'seller_stats' => [
                'active' => $activeStores,
                'inactive' => $inactiveStores,
                'total' => $activeStores + $inactiveStores,
            ],
            'reviewers_count' => $totalReviewers,
            'total_products' => Product::count(),
            'total_stores' => Store::count(),
            'total_reviews' => Review::count(),
        ]);
    }

    // Download Laporan Toko Aktif & Tidak Aktif
    public function downloadStoresStatusReport()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $activeStores = Store::has('products')->with('user')->get();
            $inactiveStores = Store::doesntHave('products')->with('user')->get();

            $data = [
                'active_stores' => $activeStores,
                'inactive_stores' => $inactiveStores,
                'generated_at' => now()->format('d/m/Y'),
            ];

            $html = view('reports.admin.stores-status', $data)->render();

            $pdf = Browsershot::html($html)
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->pdf();

            $filename = 'laporan-toko-status-' . now()->format('Y-m-d') . '.pdf';

            return Response::make($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal generate PDF: ' . $e->getMessage()], 500);
        }
    }

    // Download Laporan Toko per Provinsi
    public function downloadStoresByProvinceReport()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $storesByProvince = Store::with('user')
                ->orderBy('pic_province_name')
                ->orderBy('name')
                ->get()
                ->groupBy('pic_province_name');

            $data = [
                'stores_by_province' => $storesByProvince,
                'generated_at' => now()->format('d/m/Y'),
            ];

            $html = view('reports.admin.stores-by-province', $data)->render();

            $pdf = Browsershot::html($html)
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->pdf();

            $filename = 'laporan-toko-provinsi-' . now()->format('Y-m-d') . '.pdf';

            return Response::make($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal generate PDF: ' . $e->getMessage()], 500);
        }
    }

    // Download Laporan Produk & Rating
    public function downloadProductsRatingReport()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $reviews = Review::with(['product.store', 'product.category', 'product.reviews'])
                ->orderBy('guest_location', 'asc')
                ->get();

            $products = $reviews->map(function ($review) {
                $product = $review->product;
                return (object) [
                    'name' => $product->name,
                    'category' => $product->category,
                    'price' => $product->price,
                    'rating' => $review->rating,
                    'review_count' => $product->reviews->count(),
                    'store' => $product->store,
                    'guest_location' => $review->guest_location,
                ];
            });

            $data = [
                'products' => $products,
                'generated_at' => now()->format('d/m/Y'),
            ];

            $html = view('reports.admin.products-rating', $data)->render();

            $pdf = Browsershot::html($html)
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->pdf();

            $filename = 'laporan-produk-rating-' . now()->format('Y-m-d') . '.pdf';

            return Response::make($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal generate PDF: ' . $e->getMessage()], 500);
        }
    }
}