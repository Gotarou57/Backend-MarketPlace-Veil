<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Status Toko</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; line-height: 1.4; color: #333; padding: 15px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #667eea; }
        .header h1 { font-size: 22px; color: #667eea; margin-bottom: 8px; }
        .header p { color: #666; font-size: 10px; }
        .summary { background: linear-gradient(135deg, #e3f2fd, #bbdefb); padding: 15px; margin-bottom: 20px; border-radius: 8px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .summary-item { text-align: center; padding: 10px; background: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .summary-item strong { display: block; font-size: 24px; color: #667eea; margin-bottom: 5px; }
        .summary-item span { color: #666; font-size: 9px; text-transform: uppercase; }
        .section { margin-bottom: 30px; }
        .section h2 { font-size: 16px; color: #667eea; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #e0e0e0; }
        .active-section h2 { color: #4caf50; border-bottom-color: #4caf50; }
        .inactive-section h2 { color: #f44336; border-bottom-color: #f44336; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        table thead { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        table th { padding: 10px 8px; text-align: left; font-size: 9px; font-weight: 600; text-transform: uppercase; }
        table td { padding: 8px; border-bottom: 1px solid #e0e0e0; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #f8f9ff; }
        .status-badge { padding: 3px 8px; border-radius: 4px; font-size: 8px; font-weight: bold; display: inline-block; }
        .status-active { background: #e8f5e9; color: #2e7d32; }
        .status-inactive { background: #ffebee; color: #c62828; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; padding-top: 15px; border-top: 2px solid #e0e0e0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Daftar Akun Penjual Berdasarkan Status</h1>
        <p>Tanggal dibuat: {{ $generated_at }} oleh Admin</p>
    </div>

    

    <div class="section all-stores-section">
    {{-- Menggabungkan koleksi dan menambahkan status di Blade --}}
    @php
        $combined_stores = collect([]);

        // Tambahkan toko aktif dengan status 'aktif'
        $active_stores->each(function ($store) use ($combined_stores) {
            $store->status = 'aktif';
            $combined_stores->push($store);
        });

        // Tambahkan toko tidak aktif dengan status 'tidak aktif'
        $inactive_stores->each(function ($store) use ($combined_stores) {
            $store->status = 'tidak aktif';
            $combined_stores->push($store);
        });
        
        // Urutkan berdasarkan status (opsional)
        $combined_stores = $combined_stores->sortBy('status')->values();
    @endphp

    @if($combined_stores->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama User</th>
                    <th width="15%">Nama PIC</th>
                    <th width="15%">Nama Toko</th>
                    <th width="10%">Status</th> </tr>
            </thead>
            <tbody>
                @foreach($combined_stores as $index => $store)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $store->pic_email }}</td>
                        <td>{{ $store->pic_name }}</td>
                        <td>{{ $store->name }}</td>
                        <td>
                            @if($store->status == 'aktif')
                                <span style="color: green; font-weight: bold;">Aktif</span>
                            @else
                                <span style="color: red;">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; padding: 20px; color: #999;">Tidak ada data toko.</p>
    @endif
</div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Marketplace</p>
        <p>&copy; {{ date('Y') }} Marketplace Admin Dashboard</p>
    </div>
</body>
</html>