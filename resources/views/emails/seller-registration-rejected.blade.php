<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Ditolak</title>
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
            background: linear-gradient(135deg, #f44336 0%, #e91e63 100%);
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
        .error-box {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .error-box h3 {
            color: #c62828;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .error-list li {
            background: white;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            color: #c62828;
            font-size: 14px;
            border-left: 3px solid #f44336;
        }
        .error-list li:before {
            content: "❌ ";
            margin-right: 8px;
        }
        .info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #2196F3;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .info-box h4 {
            color: #1565c0;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .info-box ul {
            margin: 0;
            padding-left: 20px;
            color: #0d47a1;
        }
        .info-box li {
            margin-bottom: 8px;
            line-height: 1.6;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #2196F3 0%, #1976d2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
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
            color: #2196F3;
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
            <div class="icon">⚠️</div>
            <h1>Registrasi Memerlukan Perbaikan</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $sellerName }}</strong>,
            </div>

            <div class="message">
                <p>Terima kasih telah mendaftar sebagai seller di <strong>{{ config('app.name') }}</strong> dengan nama toko <strong>"{{ $storeName }}"</strong>.</p>
                <p>Sayangnya, registrasi Anda <strong>belum dapat disetujui</strong> karena terdapat beberapa data yang tidak lengkap atau tidak valid.</p>
            </div>

            <!-- Error Box -->
            <div class="error-box">
                <h3>❌ Masalah yang Ditemukan</h3>
                <ul class="error-list">
                    @foreach($errorMessages as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <h4>📝 Apa yang Harus Dilakukan?</h4>
                <ul>
                    <li>Periksa kembali data yang Anda masukkan</li>
                    <li>Pastikan semua field wajib telah diisi dengan benar</li>
                    <li>Upload foto KTP dan foto diri dengan jelas (format: JPG/PNG, maksimal 2MB)</li>
                    <li>Pastikan nomor KTP valid (16 digit)</li>
                    <li>Isi alamat lengkap termasuk RT, RW, dan Kelurahan</li>
                </ul>
            </div>

            <div class="message">
                <p>Anda dapat mencoba mendaftar kembali dengan melengkapi data yang diperlukan.</p>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/register" class="cta-button">
                    🔄 Daftar Ulang
                </a>
            </div>

            <div class="divider"></div>

            <div class="message">
                <p>Jika Anda memerlukan bantuan atau memiliki pertanyaan terkait proses registrasi, jangan ragu untuk menghubungi tim support kami.</p>
                <p>Kami berharap dapat segera menyambut Anda sebagai bagian dari komunitas seller kami!</p>
            </div>

            <div style="margin-top: 30px; color: #888; font-size: 14px;">
                Salam,<br>
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