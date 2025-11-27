<?php
// app/Http/Controllers/ProductController.php
// REPLACE file yang lama dengan ini (update bagian filter lokasi)

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Katalog produk (public - tidak perlu login)
    public function index(Request $request)
    {
        $query = Product::with(['store', 'category', 'reviews.user']);

        // Filter berdasarkan nama toko
        if ($request->has('store_name')) {
            $query->whereHas('store', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->store_name . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter berdasarkan lokasi toko (update: pic_city dan pic_province)
        if ($request->has('location')) {
            $query->whereHas('store', function ($q) use ($request) {
                $q->where('pic_city', 'like', '%' . $request->location . '%')
                  ->orWhere('pic_province', 'like', '%' . $request->location . '%');
            });
        }

        // Pencarian berdasarkan nama produk
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);

        return response()->json($products);
    }

    // Detail produk (public)
    public function show($id)
    {
        $product = Product::with(['store', 'category', 'reviews.user'])->findOrFail($id);
        return response()->json($product);
    }

    // Upload produk (seller only)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        if ($user->role !== 'seller' || !$user->store) {
            return response()->json(['error' => 'Only sellers can create products'], 403);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'store_id' => $user->store->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(6),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load(['store', 'category']),
        ], 201);
    }

    // Update produk (seller only)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();

        if ($user->store->id !== $product->store_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update($request->except('image'));

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load(['store', 'category']),
        ]);
    }

    // Hapus produk (seller only)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();

        if ($user->store->id !== $product->store_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}