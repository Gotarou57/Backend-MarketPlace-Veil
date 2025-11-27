<?php
// app/Http/Controllers/ReviewController.php
// REPLACE file yang lama dengan ini

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Tambah review (untuk semua user, termasuk guest)
    public function store(Request $request)
    {
        // Validasi dasar
        $rules = [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ];

        // Cek apakah user login
        $user = auth()->user();

        if (!$user) {
            // Jika guest, wajibkan data guest
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_phone'] = 'required|string|max:20';
            $rules['guest_email'] = 'required|email|max:255';
            $rules['guest_location'] = 'required|string|max:100';
        }

        $request->validate($rules);

        // Data review
        $reviewData = [
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        if ($user) {
            // User yang login
            $reviewData['user_id'] = $user->id;
            
            // Cek apakah user sudah pernah review produk ini
            $existingReview = Review::where('product_id', $request->product_id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingReview) {
                return response()->json(['error' => 'You have already reviewed this product'], 400);
            }
        } else {
            // Guest user
            $reviewData['guest_name'] = $request->guest_name;
            $reviewData['guest_phone'] = $request->guest_phone;
            $reviewData['guest_email'] = $request->guest_email;
            $reviewData['guest_location'] = $request->guest_location;

            // Cek apakah guest dengan email ini sudah review produk ini
            $existingReview = Review::where('product_id', $request->product_id)
                ->where('guest_email', $request->guest_email)
                ->first();

            if ($existingReview) {
                return response()->json(['error' => 'You have already reviewed this product with this email'], 400);
            }
        }

        $review = Review::create($reviewData);

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review->load('user'),
        ], 201);
    }

    // Update review (hanya untuk user yang login)
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        // Hanya user yang login dan pemilik review yang bisa update
        if (!auth()->check() || $review->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($request->all());

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review->load('user'),
        ]);
    }

    // Hapus review (hanya untuk user yang login)
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Hanya user yang login dan pemilik review yang bisa hapus
        if (!auth()->check() || $review->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }

    // Get reviews by product
    public function getByProduct($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }

    // Get available locations for dropdown
    public function getLocations()
    {
        return response()->json([
            'locations' => \Database\Seeders\LocationSeeder::getLocations()
        ]);
    }
}