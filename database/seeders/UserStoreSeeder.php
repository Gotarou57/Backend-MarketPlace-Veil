<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat User Baru (dengan role 'seller')
        $sellerUser = User::create([
            'name' => 'Seller',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'), // Password: password
            'role' => 'seller', // Asumsi kolom role ada di tabel users
            'email_verified_at' => now(),
        ]);

        // 2. Membuat Store Baru dan menghubungkannya ke User
        Store::create([
            'user_id' => $sellerUser->id,
            'name' => 'Toko Sinar Jaya',
            'description' => 'Menyediakan berbagai macam suku cadang dan aksesoris motor berkualitas.',
            
            // Data Personal in Charge (PIC)
            'pic_name' => 'Budi Santoso',
            'pic_phone' => '081234567890',
            'pic_email' => 'budi.santoso@sinarjaya.com',
            
            // Data Alamat PIC
            'pic_street' => 'Jalan Merdeka Raya No. 10',
            'pic_rt' => '001',
            'pic_rw' => '002',
            'pic_kelurahan' => 'Cilandak Timur',
            
            // Data Wilayah (Asumsi menggunakan data statis/fake)
            'pic_district_id' => '31.71.01',
            'pic_district_name' => 'Cilandak',
            'pic_city_name' => 'Kota Jakarta Selatan',
            'pic_city_id' => '31.71',
            'pic_province_id' => '31',
            'pic_province_name' => 'DKI Jakarta',
            
            // Data Identitas PIC
            'pic_ktp_number' => '3171010000000001',
            'pic_photo' => 'profile_photos/pic_budi.jpg', // Contoh path file
            'pic_ktp_file' => 'ktp_files/ktp_budi.pdf',   // Contoh path file
        ]);
        
        // 3. (Opsional) Anda bisa menambahkan user biasa jika diperlukan
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // Password: password
            'role' => 'customer', 
            'email_verified_at' => now(),
        ]);
    }
}