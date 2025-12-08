<?php
// app/Http/Controllers/LocationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Get all provinces
     * Data ini bisa diganti dengan data dari database jika diperlukan
     */
    public function getProvinces()
    {
        $provinces = [
            ['id' => '11', 'name' => 'Aceh'],
            ['id' => '12', 'name' => 'Sumatera Utara'],
            ['id' => '13', 'name' => 'Sumatera Barat'],
            ['id' => '14', 'name' => 'Riau'],
            ['id' => '15', 'name' => 'Jambi'],
            ['id' => '16', 'name' => 'Sumatera Selatan'],
            ['id' => '17', 'name' => 'Bengkulu'],
            ['id' => '18', 'name' => 'Lampung'],
            ['id' => '19', 'name' => 'Kepulauan Bangka Belitung'],
            ['id' => '21', 'name' => 'Kepulauan Riau'],
            ['id' => '31', 'name' => 'DKI Jakarta'],
            ['id' => '32', 'name' => 'Jawa Barat'],
            ['id' => '33', 'name' => 'Jawa Tengah'],
            ['id' => '34', 'name' => 'DI Yogyakarta'],
            ['id' => '35', 'name' => 'Jawa Timur'],
            ['id' => '36', 'name' => 'Banten'],
            ['id' => '51', 'name' => 'Bali'],
            ['id' => '52', 'name' => 'Nusa Tenggara Barat'],
            ['id' => '53', 'name' => 'Nusa Tenggara Timur'],
            ['id' => '61', 'name' => 'Kalimantan Barat'],
            ['id' => '62', 'name' => 'Kalimantan Tengah'],
            ['id' => '63', 'name' => 'Kalimantan Selatan'],
            ['id' => '64', 'name' => 'Kalimantan Timur'],
            ['id' => '65', 'name' => 'Kalimantan Utara'],
            ['id' => '71', 'name' => 'Sulawesi Utara'],
            ['id' => '72', 'name' => 'Sulawesi Tengah'],
            ['id' => '73', 'name' => 'Sulawesi Selatan'],
            ['id' => '74', 'name' => 'Sulawesi Tenggara'],
            ['id' => '75', 'name' => 'Gorontalo'],
            ['id' => '76', 'name' => 'Sulawesi Barat'],
            ['id' => '81', 'name' => 'Maluku'],
            ['id' => '82', 'name' => 'Maluku Utara'],
            ['id' => '91', 'name' => 'Papua'],
            ['id' => '92', 'name' => 'Papua Barat'],
        ];

        return response()->json($provinces);
    }

    /**
     * Get cities by province ID
     */
    public function getCities($provinceId)
    {
        // Sample data - dalam production, ambil dari database
        $cities = [
            // DKI Jakarta
            '31' => [
                ['id' => '3171', 'name' => 'Jakarta Selatan'],
                ['id' => '3172', 'name' => 'Jakarta Timur'],
                ['id' => '3173', 'name' => 'Jakarta Pusat'],
                ['id' => '3174', 'name' => 'Jakarta Barat'],
                ['id' => '3175', 'name' => 'Jakarta Utara'],
                ['id' => '3176', 'name' => 'Kepulauan Seribu'],
            ],
            // Jawa Barat
            '32' => [
                ['id' => '3201', 'name' => 'Kabupaten Bogor'],
                ['id' => '3202', 'name' => 'Kabupaten Sukabumi'],
                ['id' => '3203', 'name' => 'Kabupaten Cianjur'],
                ['id' => '3204', 'name' => 'Kabupaten Bandung'],
                ['id' => '3205', 'name' => 'Kabupaten Garut'],
                ['id' => '3206', 'name' => 'Kabupaten Tasikmalaya'],
                ['id' => '3207', 'name' => 'Kabupaten Ciamis'],
                ['id' => '3208', 'name' => 'Kabupaten Kuningan'],
                ['id' => '3209', 'name' => 'Kabupaten Cirebon'],
                ['id' => '3271', 'name' => 'Kota Bogor'],
                ['id' => '3272', 'name' => 'Kota Sukabumi'],
                ['id' => '3273', 'name' => 'Kota Bandung'],
                ['id' => '3274', 'name' => 'Kota Cirebon'],
                ['id' => '3275', 'name' => 'Kota Bekasi'],
                ['id' => '3276', 'name' => 'Kota Depok'],
                ['id' => '3277', 'name' => 'Kota Cimahi'],
                ['id' => '3278', 'name' => 'Kota Tasikmalaya'],
                ['id' => '3279', 'name' => 'Kota Banjar'],
            ],
            // Jawa Tengah
            '33' => [
                ['id' => '3301', 'name' => 'Kabupaten Cilacap'],
                ['id' => '3302', 'name' => 'Kabupaten Banyumas'],
                ['id' => '3303', 'name' => 'Kabupaten Purbalingga'],
                ['id' => '3304', 'name' => 'Kabupaten Banjarnegara'],
                ['id' => '3305', 'name' => 'Kabupaten Kebumen'],
                ['id' => '3306', 'name' => 'Kabupaten Purworejo'],
                ['id' => '3307', 'name' => 'Kabupaten Wonosobo'],
                ['id' => '3308', 'name' => 'Kabupaten Magelang'],
                ['id' => '3309', 'name' => 'Kabupaten Boyolali'],
                ['id' => '3310', 'name' => 'Kabupaten Klaten'],
                ['id' => '3311', 'name' => 'Kabupaten Sukoharjo'],
                ['id' => '3312', 'name' => 'Kabupaten Wonogiri'],
                ['id' => '3313', 'name' => 'Kabupaten Karanganyar'],
                ['id' => '3314', 'name' => 'Kabupaten Sragen'],
                ['id' => '3315', 'name' => 'Kabupaten Grobogan'],
                ['id' => '3316', 'name' => 'Kabupaten Blora'],
                ['id' => '3317', 'name' => 'Kabupaten Rembang'],
                ['id' => '3318', 'name' => 'Kabupaten Pati'],
                ['id' => '3319', 'name' => 'Kabupaten Kudus'],
                ['id' => '3320', 'name' => 'Kabupaten Jepara'],
                ['id' => '3321', 'name' => 'Kabupaten Demak'],
                ['id' => '3322', 'name' => 'Kabupaten Semarang'],
                ['id' => '3323', 'name' => 'Kabupaten Temanggung'],
                ['id' => '3324', 'name' => 'Kabupaten Kendal'],
                ['id' => '3371', 'name' => 'Kota Magelang'],
                ['id' => '3372', 'name' => 'Kota Surakarta'],
                ['id' => '3373', 'name' => 'Kota Salatiga'],
                ['id' => '3374', 'name' => 'Kota Semarang'],
                ['id' => '3375', 'name' => 'Kota Pekalongan'],
                ['id' => '3376', 'name' => 'Kota Tegal'],
            ],
            // Jawa Timur
            '35' => [
                ['id' => '3501', 'name' => 'Kabupaten Pacitan'],
                ['id' => '3502', 'name' => 'Kabupaten Ponorogo'],
                ['id' => '3503', 'name' => 'Kabupaten Trenggalek'],
                ['id' => '3504', 'name' => 'Kabupaten Tulungagung'],
                ['id' => '3505', 'name' => 'Kabupaten Blitar'],
                ['id' => '3506', 'name' => 'Kabupaten Kediri'],
                ['id' => '3507', 'name' => 'Kabupaten Malang'],
                ['id' => '3508', 'name' => 'Kabupaten Lumajang'],
                ['id' => '3509', 'name' => 'Kabupaten Jember'],
                ['id' => '3510', 'name' => 'Kabupaten Banyuwangi'],
                ['id' => '3511', 'name' => 'Kabupaten Bondowoso'],
                ['id' => '3512', 'name' => 'Kabupaten Situbondo'],
                ['id' => '3513', 'name' => 'Kabupaten Probolinggo'],
                ['id' => '3514', 'name' => 'Kabupaten Pasuruan'],
                ['id' => '3515', 'name' => 'Kabupaten Sidoarjo'],
                ['id' => '3516', 'name' => 'Kabupaten Mojokerto'],
                ['id' => '3517', 'name' => 'Kabupaten Jombang'],
                ['id' => '3518', 'name' => 'Kabupaten Nganjuk'],
                ['id' => '3519', 'name' => 'Kabupaten Madiun'],
                ['id' => '3520', 'name' => 'Kabupaten Magetan'],
                ['id' => '3571', 'name' => 'Kota Kediri'],
                ['id' => '3572', 'name' => 'Kota Blitar'],
                ['id' => '3573', 'name' => 'Kota Malang'],
                ['id' => '3574', 'name' => 'Kota Probolinggo'],
                ['id' => '3575', 'name' => 'Kota Pasuruan'],
                ['id' => '3576', 'name' => 'Kota Mojokerto'],
                ['id' => '3577', 'name' => 'Kota Madiun'],
                ['id' => '3578', 'name' => 'Kota Surabaya'],
                ['id' => '3579', 'name' => 'Kota Batu'],
            ],
        ];

        $result = $cities[$provinceId] ?? [];
        return response()->json($result);
    }

    /**
     * Get districts by city ID
     */
    public function getDistricts($cityId)
    {
        // Sample data kecamatan - dalam production, ambil dari database lengkap
        $districts = [
            // Kota Semarang
            '3374' => [
                ['id' => '337401', 'name' => 'Mijen'],
                ['id' => '337402', 'name' => 'Gunungpati'],
                ['id' => '337403', 'name' => 'Banyumanik'],
                ['id' => '337404', 'name' => 'Gajah Mungkur'],
                ['id' => '337405', 'name' => 'Semarang Selatan'],
                ['id' => '337406', 'name' => 'Candisari'],
                ['id' => '337407', 'name' => 'Tembalang'],
                ['id' => '337408', 'name' => 'Pedurungan'],
                ['id' => '337409', 'name' => 'Genuk'],
                ['id' => '337410', 'name' => 'Gayamsari'],
                ['id' => '337411', 'name' => 'Semarang Timur'],
                ['id' => '337412', 'name' => 'Semarang Utara'],
                ['id' => '337413', 'name' => 'Semarang Tengah'],
                ['id' => '337414', 'name' => 'Semarang Barat'],
                ['id' => '337415', 'name' => 'Tugu'],
                ['id' => '337416', 'name' => 'Ngaliyan'],
            ],
            // Kota Bandung
            '3273' => [
                ['id' => '327301', 'name' => 'Bandung Kulon'],
                ['id' => '327302', 'name' => 'Babakan Ciparay'],
                ['id' => '327303', 'name' => 'Bojongloa Kaler'],
                ['id' => '327304', 'name' => 'Bojongloa Kidul'],
                ['id' => '327305', 'name' => 'Astana Anyar'],
                ['id' => '327306', 'name' => 'Regol'],
                ['id' => '327307', 'name' => 'Lengkong'],
                ['id' => '327308', 'name' => 'Bandung Kidul'],
                ['id' => '327309', 'name' => 'Buah Batu'],
                ['id' => '327310', 'name' => 'Rancasari'],
                ['id' => '327311', 'name' => 'Gedebage'],
                ['id' => '327312', 'name' => 'Cibiru'],
                ['id' => '327313', 'name' => 'Panyileukan'],
                ['id' => '327314', 'name' => 'Ujung Berung'],
                ['id' => '327315', 'name' => 'Cinambo'],
                ['id' => '327316', 'name' => 'Arcamanik'],
                ['id' => '327317', 'name' => 'Antapani'],
                ['id' => '327318', 'name' => 'Mandalajati'],
                ['id' => '327319', 'name' => 'Kiaracondong'],
                ['id' => '327320', 'name' => 'Batununggal'],
                ['id' => '327321', 'name' => 'Sumur Bandung'],
                ['id' => '327322', 'name' => 'Andir'],
                ['id' => '327323', 'name' => 'Cicendo'],
                ['id' => '327324', 'name' => 'Bandung Wetan'],
                ['id' => '327325', 'name' => 'Cibeunying Kidul'],
                ['id' => '327326', 'name' => 'Cibeunying Kaler'],
                ['id' => '327327', 'name' => 'Coblong'],
                ['id' => '327328', 'name' => 'Sukajadi'],
                ['id' => '327329', 'name' => 'Sukasari'],
                ['id' => '327330', 'name' => 'Cidadap'],
            ],
            // Default untuk kota lain (contoh)
            'default' => [
                ['id' => '0001', 'name' => 'Kecamatan 1'],
                ['id' => '0002', 'name' => 'Kecamatan 2'],
                ['id' => '0003', 'name' => 'Kecamatan 3'],
            ],
        ];

        $result = $districts[$cityId] ?? $districts['default'];
        return response()->json($result);
    }
}