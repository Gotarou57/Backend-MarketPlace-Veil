<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
        }
        .header .icon {
            font-size: 70px;
            margin-bottom: 15px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 600;
        }
        .content {
            padding: 50px 40px;
            text-align: center;
        }
        .greeting {
            font-size: 24px;
            color: #333;
            margin-bottom: 25px;
            font-weight: 500;
        }
        .message {
            color: #555;
            line-height: 1.9;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .thank-you-box {
            background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);
            border-radius: 12px;
            padding: 40px;
            margin: 35px 0;
        }
        .thank-you-box .emoji {
            font-size: 50px;
            margin-bottom: 20px;
        }
        .thank-you-box h2 {
            color: #667eea;
            margin: 0 0 15px 0;
            font-size: 28px;
        }
        .thank-you-box p {
            color: #666;
            margin: 0;
            font-size: 16px;
            line-height: 1.7;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #777;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .signature {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
            color: #888;
            font-size: 15px;
        }
        .signature strong {
            color: #667eea;
            font-size: 17px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon">💙</div>
            <h1>Terima Kasih!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $reviewerName }}</strong>,
            </div>

            <div class="message">
                <p>Kami sangat menghargai waktu Anda untuk memberikan review.</p>
            </div>

            <!-- Thank You Box -->
            <div class="thank-you-box">
                <div class="emoji">🙏</div>
                <h2>Terima Kasih Banyak!</h2>
                <p>
                    Masukan Anda sangat berarti bagi kami dan membantu pembeli lain 
                    dalam membuat keputusan yang lebih baik.
                </p>
            </div>

            <div class="message">
                <p>
                    Kami berkomitmen untuk terus meningkatkan kualitas layanan dan produk 
                    berdasarkan feedback berharga dari Anda.
                </p>
            </div>

            <!-- Signature -->
            <div class="signature">
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