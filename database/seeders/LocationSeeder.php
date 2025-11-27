<?php
// database/seeders/LocationSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Data ini adalah contoh kota-kota di Indonesia.
     * Anda bisa menambah/mengurangi sesuai kebutuhan.
     */
    public static function getLocations()
    {
        return [
            // DKI Jakarta
            'Jakarta Pusat',
            'Jakarta Utara',
            'Jakarta Barat',
            'Jakarta Selatan',
            'Jakarta Timur',
            
            // Jawa Barat
            'Bandung',
            'Bekasi',
            'Bogor',
            'Depok',
            'Cirebon',
            'Sukabumi',
            'Tasikmalaya',
            'Karawang',
            'Purwakarta',
            
            // Jawa Tengah
            'Semarang',
            'Solo',
            'Yogyakarta',
            'Magelang',
            'Salatiga',
            'Pekalongan',
            'Tegal',
            'Purwokerto',
            'Cilacap',
            
            // Jawa Timur
            'Surabaya',
            'Malang',
            'Sidoarjo',
            'Gresik',
            'Mojokerto',
            'Kediri',
            'Blitar',
            'Madiun',
            'Pasuruan',
            'Probolinggo',
            'Jember',
            'Banyuwangi',
            
            // Bali
            'Denpasar',
            'Badung',
            'Gianyar',
            'Tabanan',
            
            // Sumatera
            'Medan',
            'Palembang',
            'Pekanbaru',
            'Padang',
            'Jambi',
            'Bengkulu',
            'Bandar Lampung',
            'Batam',
            
            // Kalimantan
            'Balikpapan',
            'Samarinda',
            'Banjarmasin',
            'Pontianak',
            'Palangkaraya',
            
            // Sulawesi
            'Makassar',
            'Manado',
            'Palu',
            'Kendari',
            'Gorontalo',
            
            // Lainnya
            'Mataram',
            'Kupang',
            'Ambon',
            'Jayapura',
        ];
    }

    public function run(): void
    {
        // Seeder ini hanya menyediakan data static
        // Tidak perlu insert ke database karena akan digunakan di dropdown frontend
    }
}