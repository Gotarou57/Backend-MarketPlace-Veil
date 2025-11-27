<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report_type }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }
        
        .header h1 {
            font-size: 22px;
            color: #667eea;
            margin-bottom: 8px;
        }
        
        .header p {
            color: #666;
            font-size: 10px;
        }
        
        .store-info {
            background: linear-gradient(135deg, #667eea15, #764ba215);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .store-info h3 {
            color: #667eea;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .store-info p {
            margin: 5px 0;
            font-size: 10px;
        }
        
        .summary {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .summary-item strong {
            display: block;
            font-size: 24px;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .summary-item span {
            color: #666;
            font-size: 9px;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        table thead {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        table th {
            padding: 12px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 10px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f8f9ff;
        }
        
        table tbody tr:hover {
            background-color: #f0f4ff;
        }
        
        .stock-badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
            display: inline-block;
        }
        
        .stock-high {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .stock-medium {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .stock-low {
            background: #ffebee;
            color: #c62828;
        }
        
        .rating {
            color: #ffa000;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $report_type }}</h1>
        <p>Diurutkan berdasarkan Jumlah Stok (Terbanyak ke Sedikit)</p>
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
            <strong>{{ $products->sum('stock') }}</strong>
            <span>Total Stok (unit)</span>
        </div>
        <div class="summary-item">
            <strong>{{ $products->where('stock', '<', 2)->count() }}</strong>
            <span>Stok Menipis</span>
        </div>
        <div class="summary-item">
            <strong>{{ number_format($products->avg('rating'), 1) }}</strong>
            <span>Rating Rata-rata</span>
        </div>
    </div>

    @if($products->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Nama Produk</th>
                    <th width="15%">Kategori</th>
                    <th width="12%">Stok</th>
                    <th width="13%">Rating</th>
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
                            <span class="stock-badge @if($product->stock < 2) stock-low @elseif($product->stock < 10) stock-medium @else stock-high @endif">
                                {{ $product->stock }} unit
                            </span>
                        </td>
                        <td class="rating">⭐ {{ number_format($product->rating, 2) }}</td>
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