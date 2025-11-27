<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report_type }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; line-height: 1.4; color: #333; padding: 15px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #ff9800; }
        .header h1 { font-size: 22px; color: #ff9800; margin-bottom: 8px; }
        .header p { color: #666; font-size: 10px; }
        .store-info { background: linear-gradient(135deg, #fff3e015, #ffe08215); padding: 15px; margin-bottom: 20px; border-radius: 8px; border-left: 4px solid #ff9800; }
        .store-info h3 { color: #ff9800; font-size: 14px; margin-bottom: 10px; }
        .store-info p { margin: 5px 0; font-size: 10px; }
        .summary { background: linear-gradient(135deg, #fff3e0, #ffe082); padding: 15px; margin-bottom: 20px; border-radius: 8px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
        .summary-item { text-align: center; padding: 10px; background: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .summary-item strong { display: block; font-size: 24px; color: #ff9800; margin-bottom: 5px; }
        .summary-item span { color: #666; font-size: 9px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        table thead { background: linear-gradient(135deg, #ff9800, #f57c00); color: white; }
        table th { padding: 12px 8px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; }
        table td { padding: 10px 8px; border-bottom: 1px solid #e0e0e0; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #fffbf5; }
        table tbody tr:hover { background-color: #fff8e1; }
        .rating-badge { padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 9px; display: inline-block; }
        .rating-excellent { background: #e8f5e9; color: #2e7d32; }
        .rating-good { background: #f1f8e9; color: #558b2f; }
        .rating-average { background: #fff3e0; color: #f57c00; }
        .rating-poor { background: #ffebee; color: #c62828; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; padding-top: 15px; border-top: 2px solid #e0e0e0; }
        .empty-state { text-align: center; padding: 50px 20px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $report_type }}</h1>
        <p>Diurutkan berdasarkan Rating (Tertinggi ke Terendah)</p>
        <p>Digenerate pada: {{ $generated_at }}</p>
    </div>

    <div class="store-info">
        <h3>🏪 Informasi Toko</h3>
        <p><strong>Nama Toko:</strong> {{ $store->name }}</p>
        @if($store->description)
            <p><strong>Deskripsi:</strong> {{ $store->description }}</p>
        @endif
        <p><strong>PIC:</strong> {{ $store->pic_name }}</p>
        <p><strong>Lokasi:</strong> {{ $store->pic_city }}, {{ $store->pic_province }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>{{ $products->count() }}</strong>
            <span>Total Produk</span>
        </div>
        <div class="summary-item">
            <strong>{{ number_format($products->avg('rating'), 1) }}</strong>
            <span>Rating Rata-rata</span>
        </div>
        <div class="summary-item">
            <strong>{{ $products->where('rating', '>=', 4)->count() }}</strong>
            <span>Rating Tinggi (≥4)</span>
        </div>
        <div class="summary-item">
            <strong>{{ $products->sum('review_count') }}</strong>
            <span>Total Review</span>
        </div>
    </div>

    @if($products->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Nama Produk</th>
                    <th width="15%">Kategori</th>
                    <th width="15%">Rating</th>
                    <th width="10%">Stok</th>
                    <th width="20%">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            <span class="rating-badge 
                                @if($product->rating >= 4.5) rating-excellent
                                @elseif($product->rating >= 3.5) rating-good
                                @elseif($product->rating >= 2.5) rating-average
                                @else rating-poor
                                @endif
                            ">
                                ⭐ {{ number_format($product->rating, 2) }} ({{ $product->review_count }})
                            </span>
                        </td>
                        <td>{{ $product->stock }} unit</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <p>📦 Belum ada produk yang terdaftar.</p>
        </div>
    @endif

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Marketplace</p>
        <p>&copy; {{ date('Y') }} {{ $store->name }} - All Rights Reserved</p>
    </div>
</body>
</html>