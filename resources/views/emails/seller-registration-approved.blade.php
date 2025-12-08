<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header .icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            color: #555;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .credentials-box {
            background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);
            border-left: 4px solid #667eea;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .credentials-box h3 {
            color: #667eea;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .credential-item {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }
        .credential-item:last-child {
            margin-bottom: 0;
        }
        .credential-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .credential-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .instructions {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .instructions h4 {
            color: #667eea;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .instructions ol {
            margin: 0;
            padding-left: 20px;
            color: #555;
        }
        .instructions li {
            margin-bottom: 10px;
            line-height: 1.6;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 25px;
            text-align: center;
            color: #777;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e0e0e0, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon">🎉</div>
            <h1>Selamat! Registrasi Berhasil</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $sellerName }}</strong>,
            </div>

            <div class="message">
                <p>Selamat bergabung di <strong>{{ config('app.name') }}</strong>!</p>
                <p>Registrasi toko <strong>"{{ $storeName }}"</strong> Anda telah berhasil diverifikasi dan disetujui. Anda sekarang dapat mulai berjualan di marketplace kami.</p>
            </div>

            <!-- Credentials Box -->
            <div class="credentials-box">
                <h3>🔐 Informasi Login Anda</h3>
                
                <div class="credential-item">
                    <div class="credential-label">Username</div>
                    <div class="credential-value">{{ $username }}</div>
                </div>

                <div class="credential-item">
                    <div class="credential-label">Password</div>
                    <div class="credential-value">{{ $password }}</div>
                </div>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <p><strong>⚠️ PENTING:</strong> Simpan username dan password ini dengan aman. Kami sangat menyarankan Anda untuk mengganti password setelah login pertama kali.</p>
            </div>

            

            <div class="divider"></div>

            <!-- Instructions -->
            <div class="instructions">
                <h4>📋 Langkah Selanjutnya</h4>
                <ol>
                    <li>Login menggunakan username dan password di atas</li>
                    <li>Lengkapi profil toko Anda jika diperlukan</li>
                    <li>Mulai menambahkan produk untuk dijual</li>
                    <li>Kelola pesanan dan transaksi dari dashboard seller</li>
                    <li>Ubah password default Anda di menu pengaturan</li>
                </ol>
            </div>

            <div class="message">
                <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi tim support kami.</p>
                <p>Terima kasih telah memilih <strong>{{ config('app.name') }}</strong> sebagai platform berjualan Anda!</p>
            </div>

            <div style="margin-top: 30px; color: #888; font-size: 14px;">
                Salam hangat,<br>
                <strong>Tim {{ config('app.name') }}</strong>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            <p>Butuh bantuan? <a href="{{ config('app.url') }}/contact">Hubungi Support</a></p>
            <p style="margin-top: 15px;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>