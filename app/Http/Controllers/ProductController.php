<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['store', 'category', 'reviews.user', 'images']);

        if ($request->has('store_name')) {
            $query->whereHas('store', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->store_name . '%');
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('location')) {
            $query->whereHas('store', function ($q) use ($request) {
                $q->where('pic_city_name', 'like', '%' . $request->location . '%')
                  ->orWhere('pic_province_name', 'like', '%' . $request->location . '%');
            });
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // BARU: Filter berdasarkan kondisi
        if ($request->has('kondisi') && in_array($request->kondisi, ['Baru', 'Bekas'])) {
            $query->where('kondisi', $request->kondisi);
        }

        $products = $query->paginate(12);

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['store', 'category', 'reviews.user', 'images'])->findOrFail($id);
        return response()->json($product);
    }

    // Upload produk dengan multiple images
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimal_pemesanan' => 'required|integer|min:1',        // BARU
            'berat_satuan' => 'required|integer|min:1',             // BARU
            'kondisi' => 'required|in:Baru,Bekas',                  // BARU
        ], [
            // BARU: Custom error messages
            'minimal_pemesanan.required' => 'Minimal pemesanan harus diisi',
            'minimal_pemesanan.min' => 'Minimal pemesanan minimal 1',
            'berat_satuan.required' => 'Berat satuan harus diisi',
            'berat_satuan.min' => 'Berat satuan minimal 1 gram',
            'kondisi.required' => 'Kondisi produk harus dipilih',
            'kondisi.in' => 'Kondisi hanya boleh Baru atau Bekas',
        ]);

        $user = auth()->user();

        if ($user->role !== 'seller' || !$user->store) {
            return response()->json(['error' => 'Only sellers can create products'], 403);
        }

        // Create product
        $product = Product::create([
            'store_id' => $user->store->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(6),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'minimal_pemesanan' => $request->minimal_pemesanan,  // BARU
            'berat_satuan' => $request->berat_satuan,            // BARU
            'kondisi' => $request->kondisi,                      // BARU
        ]);

        // Upload multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'display_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load(['store', 'category', 'images']),
        ], 201);
    }

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
            'images' => 'sometimes|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimal_pemesanan' => 'sometimes|integer|min:1',      // BARU
            'berat_satuan' => 'sometimes|integer|min:1',           // BARU
            'kondisi' => 'sometimes|in:Baru,Bekas',                // BARU
        ], [
            // BARU: Custom error messages
            'minimal_pemesanan.min' => 'Minimal pemesanan minimal 1',
            'berat_satuan.min' => 'Berat satuan minimal 1 gram',
            'kondisi.in' => 'Kondisi hanya boleh Baru atau Bekas',
        ]);

        $product->update($request->except('images'));

        // Handle new images if provided
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }

            // Upload new images
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'display_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load(['store', 'category', 'images']),
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();

        if ($user->store->id !== $product->store_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}