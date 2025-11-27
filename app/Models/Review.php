<?php
// app/Models/Review.php
// REPLACE file yang lama dengan ini

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'guest_name',
        'guest_phone',
        'guest_email',
        'guest_location',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: Dapatkan nama reviewer (user atau guest)
    public function getReviewerNameAttribute()
    {
        if ($this->user_id && $this->user) {
            return $this->user->name;
        }
        return $this->guest_name ?? 'Anonim';
    }

    // Helper: Dapatkan lokasi reviewer
    public function getReviewerLocationAttribute()
    {
        if ($this->guest_location) {
            return $this->guest_location;
        }
        if ($this->user_id && $this->user) {
            return $this->user->address ?? 'Tidak diketahui';
        }
        return 'Tidak diketahui';
    }

    // Helper: Cek apakah review dari guest
    public function isGuest()
    {
        return $this->user_id === null;
    }

    // Event listener untuk update rating produk
    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            $review->product->updateRating();
        });

        static::updated(function ($review) {
            $review->product->updateRating();
        });

        static::deleted(function ($review) {
            $review->product->updateRating();
        });
    }
}