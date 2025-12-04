<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #2196F3;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }

        .otp-code {
            background: white;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 10px;
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            border: 2px dashed #2196F3;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Verifikasi Perubahan Email SAKEDAP</h2>
        </div>
        <div class="content">
            <p>Anda telah meminta untuk mengubah email pada akun Anda.</p>
            <p>Gunakan kode OTP berikut untuk memverifikasi perubahan:</p>
            <div class="otp-code">
                {{ $otp }}
            </div>
            <p><strong>Kode ini berlaku selama 5 menit.</strong></p>
            <p>Jika Anda tidak meminta perubahan ini, abaikan email ini.</p>
        </div>
        <div class="footer">
            <p>Email otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
