<?php
// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Get all categories (public)
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return response()->json($categories);
    }

    // Get category by slug (public)
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->withCount('products')
            ->firstOrFail();
        return response()->json($category);
    }

    // Create category (admin only - untuk demo, tidak ada middleware admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }
}