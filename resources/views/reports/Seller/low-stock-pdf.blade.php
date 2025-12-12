<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report_type }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; line-height: 1.4; color: #333; padding: 15px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #f44336; }
        .header h1 { font-size: 22px; color: #f44336; margin-bottom: 8px; }
        .header p { color: #666; font-size: 10px; }
        .alert { background: #ffebee; border-left: 4px solid #f44336; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .alert h3 { color: #f44336; font-size: 14px; margin-bottom: 8px; }
        .alert p { font-size: 10px; }
        .store-info { background: #fafafa; padding: 15px; margin-bottom: 20px; border-radius: 8px; border-left: 4px solid #667eea; }
        .store-info h3 { color: #667eea; font-size: 14px; margin-bottom: 10px; }
        .store-info p { margin: 5px 0; font-size: 10px; }
        .summary { background: linear-gradient(135deg, #fff3e0, #ffe082); padding: 15px; margin-bottom: 20px; border-radius: 8px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .summary-item { text-align: center; padding: 10px; background: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .summary-item strong { display: block; font-size: 24px; color: #f44336; margin-bottom: 5px; }
        .summary-item span { color: #666; font-size: 9px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        table thead { background: linear-gradient(135deg, #f44336, #d32f2f); color: white; }
        table th { padding: 12px 8px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; }
        table td { padding: 10px 8px; border-bottom: 1px solid #e0e0e0; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #fff5f5; }
        table tbody tr:hover { background-color: #ffebee; }
        .stock-critical { background: #ffebee; color: #c62828; font-weight: bold; padding: 4px 10px; border-radius: 4px; font-size: 9px; display: inline-block; }
        .priority-badge { color: white; padding: 4px 10px; border-radius: 4px; font-size: 9px; font-weight: bold; display: inline-block; }
        .priority-high { background: #f44336; }
        .priority-medium { background: #ff9800; }
        .recommendation { margin-top: 20px; padding: 15px; background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 8px; border-left: 4px solid #2196f3; }
        .recommendation h3 { color: #1976d2; font-size: 14px; margin-bottom: 10px; }
        .recommendation ul { margin: 10px 0 0 20px; }
        .recommendation li { margin: 5px 0; font-size: 10px; }
        .safe-message { text-align: center; padding: 40px 20px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 8px; }
        .safe-message h3 { color: #2e7d32; font-size: 18px; margin-bottom: 10px; }
        .safe-message p { color: #666; font-size: 11px; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; padding-top: 15px; border-top: 2px solid #e0e0e0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $report_type }}</h1>
        <p>Tanggal dibuat: {{ $generated_at }} oleh {{ $store->name }}</p>
    </div>

    

    
    @if($products->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Produk</th>
                    <th width="15%">Kategori</th>
                    <th width="12%">Harga</th>
                    <th width="12%">Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category->name }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="stock-critical">{{ $product->stock }} unit</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else
        <div class="safe-message">
            <h3>✅ Semua Produk Aman!</h3>
            <p>Tidak ada produk dengan stok menipis. Stok semua produk masih mencukupi.</p>
        </div>
    @endif

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Marketplace</p>
        <p>&copy; {{ date('Y') }} {{ $store->name }} - All Rights Reserved</p>
    </div>
</body>
</html>