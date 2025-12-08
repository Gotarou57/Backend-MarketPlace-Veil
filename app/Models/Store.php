<?php
// app/Models/Store.php
// REPLACE dengan ini

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'pic_name',
        'pic_phone',
        'pic_email',
        'pic_street',
        'pic_rt',
        'pic_rw',
        'pic_kelurahan',
        'pic_province_id',
        'pic_province_name',
        'pic_city_id',
        'pic_city_name',
        'pic_district_id',
        'pic_district_name',
        'pic_ktp_number',
        'pic_photo',
        'pic_ktp_file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Helper: Check if store is active (has products)
    public function isActive()
    {
        return $this->products()->count() > 0;
    }

    // Helper: Get full address
    public function getFullAddressAttribute()
    {
        return "{$this->pic_street}, RT {$this->pic_rt}/RW {$this->pic_rw}, {$this->pic_kelurahan}, {$this->pic_district_name}, {$this->pic_city_name}, {$this->pic_province_name}";
    }
}