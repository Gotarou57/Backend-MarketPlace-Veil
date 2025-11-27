<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Produk elektronik dan gadget'],
            ['name' => 'Fashion', 'description' => 'Pakaian dan aksesoris'],
            ['name' => 'Makanan & Minuman', 'description' => 'Produk makanan dan minuman'],
            ['name' => 'Kesehatan & Kecantikan', 'description' => 'Produk kesehatan dan kecantikan'],
            ['name' => 'Olahraga', 'description' => 'Peralatan dan perlengkapan olahraga'],
            ['name' => 'Buku & Alat Tulis', 'description' => 'Buku dan peralatan tulis'],
            ['name' => 'Rumah Tangga', 'description' => 'Perlengkapan rumah tangga'],
            ['name' => 'Otomotif', 'description' => 'Aksesoris dan suku cadang kendaraan'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}