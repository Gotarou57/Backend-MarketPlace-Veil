<?php
// routes/api.php
// TAMBAHKAN route admin verification

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminVerificationController; // BARU
use App\Http\Controllers\LocationController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Katalog produk (public)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Categories (public)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// Reviews (public)
Route::get('/products/{productId}/reviews', [ReviewController::class, 'getByProduct']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/locations', [ReviewController::class, 'getLocations']);

// Location API (Provinsi, Kota, Kecamatan)
Route::get('/provinces', [LocationController::class, 'getProvinces']);
Route::get('/cities/{provinceId}', [LocationController::class, 'getCities']);
Route::get('/districts/{cityId}', [LocationController::class, 'getDistricts']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    // Products (seller only)
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Reviews (update & delete)
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    // Seller Dashboard
    Route::get('/seller/dashboard', [SellerDashboardController::class, 'dashboard']);
    Route::get('/seller/products', [SellerDashboardController::class, 'getMyProducts']);
    Route::get('/seller/products/{productId}/reviews', [SellerDashboardController::class, 'getProductReviewsByLocation']);
    
    // Seller Reports
    Route::get('/seller/reports/stock', [SellerDashboardController::class, 'downloadStockReport']);
    Route::get('/seller/reports/rating', [SellerDashboardController::class, 'downloadRatingReport']);
    Route::get('/seller/reports/low-stock', [SellerDashboardController::class, 'downloadLowStockReport']);
    Route::get('/seller/reports/stock-csv', [SellerDashboardController::class, 'downloadStockReportCSV']);

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard']);
    
    // Admin Reports
    Route::get('/admin/reports/stores-status', [AdminDashboardController::class, 'downloadStoresStatusReport']);
    Route::get('/admin/reports/stores-by-province', [AdminDashboardController::class, 'downloadStoresByProvinceReport']);
    Route::get('/admin/reports/products-rating', [AdminDashboardController::class, 'downloadProductsRatingReport']);

    // ========== BARU: ADMIN VERIFICATION ROUTES ==========
    Route::middleware('admin')->prefix('admin/verification')->group(function () {
        // Get pending stores
        Route::get('/pending', [AdminVerificationController::class, 'getPendingStores']);
        
        // Get store detail
        Route::get('/stores/{id}', [AdminVerificationController::class, 'getStoreDetail']);
        
        // Approve store
        Route::post('/stores/{id}/approve', [AdminVerificationController::class, 'approveStore']);
        
        // Reject store
        Route::post('/stores/{id}/reject', [AdminVerificationController::class, 'rejectStore']);
        
        // Get verification history
        Route::get('/history', [AdminVerificationController::class, 'getVerificationHistory']);
    });
    // ====================================================

    // Categories
    Route::post('/categories', [CategoryController::class, 'store']);
});