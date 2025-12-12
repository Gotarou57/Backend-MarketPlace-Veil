<?php

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
        'minimal_pemesanan',  // BARU
        'berat_satuan',       // BARU
        'kondisi',            // BARU
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'minimal_pemesanan' => 'integer',  // BARU
        'berat_satuan' => 'integer',       // BARU
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

    // Relationship dengan ProductImage
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    // Get primary image
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

    // BARU: Helper untuk format berat
    public function getBeratFormatAttribute()
    {
        if ($this->berat_satuan >= 1000) {
            return number_format($this->berat_satuan / 1000, 2) . ' kg';
        }
        return $this->berat_satuan . ' gram';
    }

    // BARU: Helper untuk kondisi badge
    public function getKondisiBadgeAttribute()
    {
        return $this->kondisi === 'Baru' ? '🆕 Baru' : '♻️ Bekas';
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