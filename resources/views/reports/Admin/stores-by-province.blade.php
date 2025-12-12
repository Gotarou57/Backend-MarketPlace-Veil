<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Toko per Provinsi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; line-height: 1.4; color: #333; padding: 15px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #4caf50; }
        .header h1 { font-size: 22px; color: #4caf50; margin-bottom: 8px; }
        .header p { color: #666; font-size: 10px; }
        .province-section { margin-bottom: 30px; page-break-inside: avoid; }
        .province-header { background: linear-gradient(135deg, #4caf50, #388e3c); color: white; padding: 10px 15px; border-radius: 6px; margin-bottom: 10px; }
        .province-header h2 { font-size: 14px; margin: 0; }
        .province-header .count { font-size: 10px; opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        table thead { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        table th { padding: 10px 8px; text-align: left; font-size: 9px; font-weight: 600; text-transform: uppercase; }
        table td { padding: 8px; border-bottom: 1px solid #e0e0e0; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #f8f9ff; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; padding-top: 15px; border-top: 2px solid #e0e0e0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Daftar Toko Berdasarkan Lokasi Provinsi</h1>
        <p>Tanggal dibuat: {{ $generated_at }} oleh Admin</p>
    </div>

    @php
        // 1. Menggabungkan (Flattening) semua koleksi toko dari setiap provinsi
        // $stores_by_province adalah array/collection of collections.
        // Kita gunakan collapse() untuk menggabungkannya menjadi satu koleksi tunggal.
        $all_stores = collect($stores_by_province)->collapse();

        // 2. Sortir koleksi berdasarkan Provinsi untuk tampilan yang lebih rapi (Opsional)
        $all_stores = $all_stores->sortBy('pic_province_name')->values();
        
        $total_stores = $all_stores->count();
    @endphp

    @if($total_stores > 0)
        <div class="all-stores-section">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Toko</th>
                        <th width="20%">Nama PIC</th>
                        <th width="20%">Provinsi</th> 
                        </tr>
                </thead>
                <tbody>
                    @foreach($all_stores as $index => $store)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $store->name }}</td>
                            <td>{{ $store->pic_name }}</td>
                            <td>{{ $store->pic_province_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p style="text-align: center; padding: 20px; color: #999;">Tidak ada data toko ditemukan.</p>
    @endif
    
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Marketplace</p>
        <p>&copy; {{ date('Y') }} Marketplace Admin Dashboard</p>
    </div>
</body>
</html>