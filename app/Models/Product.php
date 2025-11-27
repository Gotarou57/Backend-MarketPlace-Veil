<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'rating',
        'review_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Update rating produk
    public function updateRating()
    {
        $reviews = $this->reviews;
        $this->review_count = $reviews->count();
        $this->rating = $reviews->count() > 0 ? $reviews->avg('rating') : 0;
        $this->save();
    }
}