<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat</title>
    <style>
        @page {
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            width: 297mm;
            height: 210mm;
        }
        .certificate-container {
            background: white;
            padding: 50px 60px;
            border: 15px solid #f0f0f0;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            height: 100%;
            position: relative;
        }
        .border-inner {
            border: 3px solid #667eea;
            padding: 30px;
            height: 100%;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
        }
        .title {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 3px;
            margin: 15px 0;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 18px;
            color: #666;
            font-style: italic;
            margin-bottom: 10px;
        }
        .nomor {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }
        .content {
            text-align: center;
            margin: 40px 0;
        }
        .text-award {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }
        .recipient-name {
            font-size: 42px;
            font-weight: bold;
            color: #764ba2;
            margin: 25px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
            padding-bottom: 10px;
        }
        .description {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
            margin: 25px auto;
            max-width: 85%;
        }
        .footer {
            position: absolute;
            bottom: 30px;
            right: 60px;
            text-align: center;
        }
        .date {
            font-size: 14px;
            color: #666;
            margin-bottom: 60px;
        }
        .signature-line {
            border-top: 2px solid #333;
            width: 200px;
            margin: 0 auto;
            padding-top: 10px;
        }
        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .signature-title {
            font-size: 13px;
            color: #666;
        }
        .decorative-corner {
            position: absolute;
            width: 100px;
            height: 100px;
        }
        .corner-top-left {
            top: 0;
            left: 0;
            border-top: 5px solid #667eea;
            border-left: 5px solid #667eea;
        }
        .corner-top-right {
            top: 0;
            right: 0;
            border-top: 5px solid #764ba2;
            border-right: 5px solid #764ba2;
        }
        .corner-bottom-left {
            bottom: 0;
            left: 0;
            border-bottom: 5px solid #764ba2;
            border-left: 5px solid #764ba2;
        }
        .corner-bottom-right {
            bottom: 0;
            right: 0;
            border-bottom: 5px solid #667eea;
            border-right: 5px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="border-inner">
            <div class="decorative-corner corner-top-left"></div>
            <div class="decorative-corner corner-top-right"></div>
            <div class="decorative-corner corner-bottom-left"></div>
            <div class="decorative-corner corner-bottom-right"></div>
            
            <div class="header">
                <div class="logo">W</div>
                <div class="subtitle">Workshop Framework</div>
                <h1 class="title">SERTIFIKAT</h1>
                <p class="subtitle">Certificate of Achievement</p>
                <p class="nomor">No. {{ $nomor_sertifikat }}</p>
            </div>
            
            <div class="content">
                <p class="text-award">Diberikan kepada:</p>
                
                <h2 class="recipient-name">{{ $nama }}</h2>
                
                <p class="description">
                    Atas partisipasi dan dedikasi dalam menyelesaikan Workshop Framework Laravel 
                    dengan baik. Telah menunjukkan kemampuan dalam memahami konsep-konsep dasar 
                    pengembangan aplikasi web menggunakan Framework Laravel, termasuk implementasi 
                    autentikasi Google OAuth, sistem OTP, dan generate PDF.
                </p>
            </div>
            
            <div class="footer">
                <p class="date">{{ $tanggal }}</p>
                <div class="signature-line">
                    <p class="signature-name">Ketua Panitia</p>
                    <p class="signature-title">Workshop Framework Laravel</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
