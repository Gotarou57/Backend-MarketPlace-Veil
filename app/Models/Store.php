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

    // BARU: Relasi ke admin yang melakukan verifikasi
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helper: Check if store is active (has products)
    public function isActive()
    {
        return $this->products()->count() > 0;
    }

    // BARU: Helper status verifikasi
    public function isPending()
    {
        return $this->verification_status === 'pending';
    }

    public function isApproved()
    {
        return $this->verification_status === 'approved';
    }

    public function isRejected()
    {
        return $this->verification_status === 'rejected';
    }

    // Helper: Get full address
    public function getFullAddressAttribute()
    {
        return "{$this->pic_street}, RT {$this->pic_rt}/RW {$this->pic_rw}, {$this->pic_kelurahan}, {$this->pic_district_name}, {$this->pic_city_name}, {$this->pic_province_name}";
    }

    // BARU: Scope untuk filter berdasarkan status
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }
}