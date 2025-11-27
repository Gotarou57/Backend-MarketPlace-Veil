<?php
// routes/api.php
// REPLACE file yang lama dengan ini

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SellerDashboardController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Katalog produk
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// Reviews
Route::get('/products/{productId}/reviews', [ReviewController::class, 'getByProduct']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/locations', [ReviewController::class, 'getLocations']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    // Store Setup
    Route::post('/setup-store', [AuthController::class, 'setupStore']);

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
    
    // BARU: Download PDF Reports (Browsershot)
    Route::get('/seller/reports/stock', [SellerDashboardController::class, 'downloadStockReport']);
    Route::get('/seller/reports/rating', [SellerDashboardController::class, 'downloadRatingReport']);
    Route::get('/seller/reports/low-stock', [SellerDashboardController::class, 'downloadLowStockReport']);
    Route::get('/seller/reports/stock-csv', [SellerDashboardController::class, 'downloadStockReportCSV']);

    // Categories
    Route::post('/categories', [CategoryController::class, 'store']);
});