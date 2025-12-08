<?php
// app/Models/Product.php
// REPLACE dengan ini

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
        'image', // Keep untuk backward compatibility
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

    // BARU: Relationship dengan ProductImage
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    // BARU: Get primary image
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    // Helper: Get primary image URL
    public function getPrimaryImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return asset('storage/' . $primaryImage->image_path);
        }
        return null;
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