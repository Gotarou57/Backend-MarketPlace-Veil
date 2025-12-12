<?php
// app/Http/Controllers/SellerDashboardController.php
// REPLACE file yang lama dengan ini

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Spatie\Browsershot\Browsershot;

class SellerDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'seller' || !$user->store) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $store = $user->store;
        $locationFilter = $request->get('location');
        $productsQuery = $store->products()->with(['category', 'reviews']);

        if ($locationFilter) {
            $productsQuery->with(['reviews' => function ($query) use ($locationFilter) {
                $query->where('guest_location', $locationFilter);
            }]);
        }

        $products = $productsQuery->get()->map(function ($product) use ($locationFilter) {
            $reviews = $product->reviews;
            $filteredRating = $reviews->count() > 0 ? $reviews->avg('rating') : 0;
            $filteredReviewCount = $reviews->count();

            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'rating' => $locationFilter ? round($filteredRating, 2) : $product->rating,
                'review_count' => $locationFilter ? $filteredReviewCount : $product->review_count,
                'category' => $product->category->name,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'created_at' => $product->created_at,
            ];
        });

        $stats = [
            'total_products' => $products->count(),
            'total_stock' => $products->sum('stock'),
            'low_stock_products' => $products->where('stock', '<', 2)->count(),
            'average_rating' => $products->count() > 0 ? $products->avg('rating') : 0,
        ];

        $availableLocations = $store->products()
            ->with('reviews')
            ->get()
            ->pluck('reviews')
            ->flatten()
            ->pluck('guest_location')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return response()->json([
            'store' => $store,
            'products' => $products,
            'stats' => $stats,
            'available_locations' => $availableLocations,
            'current_filter' => $locationFilter,
        ]);
    }

    public function getMyProducts()
    {
        $user = auth()->user();

        if ($user->role !== 'seller' || !$user->store) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $products = $user->store->products()
            ->with(['category', 'reviews'])
            ->latest()
            ->get();

        return response()->json($products);
    }

    public function getProductReviewsByLocation(Request $request, $productId)
    {
        $user = auth()->user();

        if ($user->role !== 'seller' || !$user->store) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $product = $user->store->products()->findOrFail($productId);
        $location = $request->get('location');
        $reviewsQuery = $product->reviews()->with('user');

        if ($location) {
            $reviewsQuery->where('guest_location', $location);
        }

        $reviews = $reviewsQuery->latest()->get();

        $stats = [
            'total_reviews' => $reviews->count(),
            'average_rating' => $reviews->avg('rating'),
            'location' => $location,
        ];

        return response()->json([
            'reviews' => $reviews,
            'stats' => $stats,
        ]);
    }

    // BARU: Download Laporan Stok PDF
    public function downloadStockReport()
{
    \Log::info('SELLER REPORT HIT', [
        'user' => auth()->user()->id ?? 'null',
        'role' => auth()->user()->role ?? 'null'
    ]);

    $user = auth()->user();

    if ($user->role !== 'seller' || !$user->store) {
        \Log::warning('SELLER REPORT UNAUTHORIZED', [
            'user' => $user->id ?? 'null',
            'has_store' => $user->store ? 'yes' : 'no'
        ]);

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    try {
        \Log::info('SELLER REPORT START PROCESS', [
            'store_id' => $user->store->id,
        ]);

        $store = $user->store;
        $products = $store->products()
            ->with('category')
            ->orderBy('stock', 'desc')
            ->get();

        \Log::info('SELLER REPORT PRODUCT COUNT', [
            'count' => $products->count()
        ]);

        $data = [
            'store' => $store,
            'products' => $products,
            'report_type' => 'Laporan Daftar Produk Berdasarkan Stok',
            'generated_at' => now()->format('d/m/Y'),
        ];

        // Generate HTML dari view
        $html = view('reports.seller.stock-pdf', $data)->render();

        \Log::info('SELLER REPORT HTML GENERATED');

        // Generate PDF menggunakan Browsershot
        $pdf = Browsershot::html($html)
            ->setOption('landscape', false)
            ->margins(10, 10, 10, 10)
            ->format('A4')
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->pdf();

        \Log::info('SELLER REPORT PDF GENERATED');

        $filename = 'laporan-stok-' . now()->format('Y-m-d') . '.pdf';

        return Response::make($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    } catch (\Exception $e) {

        // LOG ERROR SANGAT DETAIL
        \Log::error('SELLER REPORT ERROR', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString()
        ]);

        return response()->json([
            'error' => 'Gagal generate PDF: ' . $e->getMessage()
        ], 500);
    }
}

    // BARU: Download Laporan Rating PDF
    public function downloadRatingReport()
    {
        $user = auth()->user();

        if ($user->role !== 'seller' || !$user->store) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $store = $user->store;
            $products = $store->products()
                ->with('category')
                ->orderBy('rating', 'desc')
                ->get();

            $data = [
                'store' => $store,
                'products' => $products,
                'report_type' => 'Laporan Daftar Produk Berdasarkan Rating',
                'generated_at' => now()->format('d/m/Y'),
            ];

            $html = view('reports.seller.rating-pdf', $data)->render();

            $pdf = Browsershot::html($html)
                ->setOption('landscape', false)
                ->margins(10, 10, 10, 10)
                ->format('A4')
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->pdf();

            $filename = 'laporan-rating-' . now()->format('Y-m-d') . '.pdf';

            return Response::make($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    // BARU: Download Laporan Stok Menipis PDF
    public function downloadLowStockReport()
    {
        $user = auth()->user();

        if ($user->role !== 'seller' || !$user->store) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $store = $user->store;
            $products = $store->products()
                ->with('category')
                ->where('stock', '<', 2)
                ->orderBy('stock', 'asc')
                ->get();

            $data = [
                'store' => $store,
                'products' => $products,
                'report_type' => 'Laporan Daftar Produk Segera Dipesan',
                'generated_at' => now()->format('d/m/Y'),
            ];

            $html = view('reports.seller.low-stock-pdf', $data)->render();

            $pdf = Browsershot::html($html)
                ->setOption('landscape', false)
                ->margins(10, 10, 10, 10)
                ->format('A4')
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->pdf();

            $filename = 'laporan-stok-menipis-' . now()->format('Y-m-d') . '.pdf';

            return Response::make($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    // BARU: Download CSV
    public function downloadStockReportCSV()
    {
        $user = auth()->user();

        if ($user->role !== 'seller' || !$user->store) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $store = $user->store;
        $products = $store->products()
            ->with('category')
            ->orderBy('stock', 'desc')
            ->get();

        $csvData = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvData .= "Nama Produk,Kategori,Stok,Rating,Harga\n";
        
        foreach ($products as $product) {
            $csvData .= sprintf(
                '"%s","%s",%d,%.2f,"Rp %s"' . "\n",
                str_replace('"', '""', $product->name),
                str_replace('"', '""', $product->category->name),
                $product->stock,
                $product->rating,
                number_format($product->price, 0, ',', '.')
            );
        }

        $filename = 'laporan-stok-' . now()->format('Y-m-d') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}