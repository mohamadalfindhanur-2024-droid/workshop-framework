<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Undangan</title>
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
            padding: 20mm 25mm;
            line-height: 1.6;
            color: #333;
        }
        .header {
            border-bottom: 4px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
        }
        .header-content {
            display: table;
            width: 100%;
        }
        .logo-section {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }
        .logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            margin: 0;
        }
        .header-text {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding-left: 20px;
        }
        .institution-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .faculty-name {
            font-size: 18px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 2px;
        }
        .address {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        .header-bottom-line {
            border-top: 1px solid #000;
            margin-top: 3px;
        }
        
        .document-info {
            margin: 25px 0 30px 0;
            font-size: 13px;
        }
        .document-info table {
            width: 100%;
            border: none;
        }
        .document-info td {
            padding: 3px 0;
            vertical-align: top;
        }
        .document-info .label {
            width: 100px;
        }
        .document-info .separator {
            width: 20px;
        }
        
        .title {
            text-align: center;
            margin: 30px 0 35px 0;
        }
        .title h1 {
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .title .nomor {
            font-size: 12px;
            color: #666;
        }
        
        .content {
            text-align: justify;
            margin-bottom: 30px;
            font-size: 13px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .salutation {
            margin-bottom: 20px;
        }
        .table-acara {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .table-acara td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .table-acara .label-col {
            width: 150px;
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .closing {
            margin-top: 40px;
            text-align: justify;
            font-size: 13px;
        }
        
        .signature {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 200px;
        }
        .signature .place-date {
            margin-bottom: 70px;
        }
        .signature .name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .signature .nip {
            font-size: 11px;
            color: #666;
        }
        
        .footer-note {
            clear: both;
            margin-top: 120px;
            font-size: 10px;
            color: #999;
            font-style: italic;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- HEADER SURAT -->
    <div class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo">U</div>
            </div>
            <div class="header-text">
                <div class="institution-name">Universitas Workshop Framework</div>
                <div class="faculty-name">Fakultas Ilmu Komputer</div>
                <div class="address">
                    Jl. Pendidikan No. 123, Jakarta 12345 | Telp: (021) 1234567 | Email: fik@university.ac.id
                </div>
            </div>
        </div>
        <div class="header-bottom-line"></div>
    </div>
    
    <!-- NOMOR DAN PERIHAL -->
    <div class="document-info">
        <table>
            <tr>
                <td class="label">Nomor</td>
                <td class="separator">:</td>
                <td>{{ $nomor_surat }}</td>
            </tr>
            <tr>
                <td class="label">Lampiran</td>
                <td class="separator">:</td>
                <td>-</td>
            </tr>
            <tr>
                <td class="label">Perihal</td>
                <td class="separator">:</td>
                <td><strong>Undangan Workshop Framework Laravel</strong></td>
            </tr>
        </table>
    </div>
    
    <!-- ISI SURAT -->
    <div class="content">
        <div class="salutation">
            <p>Kepada Yth.<br>
            <strong>{{ $penerima }}</strong><br>
            di tempat</p>
        </div>
        
        <p>Dengan hormat,</p>
        
        <p style="text-indent: 40px;">
            Sehubungan dengan akan dilaksanakannya kegiatan Workshop Framework Laravel, 
            kami mengundang Bapak/Ibu untuk hadir dalam acara tersebut.
        </p>
        
        <p>Adapun kegiatan akan dilaksanakan pada:</p>
        
        <table class="table-acara">
            <tr>
                <td class="label-col">Hari/Tanggal</td>
                <td>{{ $tanggal_acara }}</td>
            </tr>
            <tr>
                <td class="label-col">Waktu</td>
                <td>{{ $waktu }}</td>
            </tr>
            <tr>
                <td class="label-col">Tempat</td>
                <td>{{ $tempat }}</td>
            </tr>
            <tr>
                <td class="label-col">Acara</td>
                <td>Workshop Framework Laravel: Google OAuth, OTP, dan Generate PDF</td>
            </tr>
        </table>
        
        <p style="text-indent: 40px;">
            Demikian undangan ini kami sampaikan. Atas perhatian dan kehadiran 
            Bapak/Ibu, kami ucapkan terima kasih.
        </p>
    </div>
    
    <!-- TTD -->
    <div class="signature">
        <div class="place-date">
            Jakarta, {{ $tanggal_surat }}
        </div>
        <div class="position">
            <strong>Dekan</strong><br>
            Fakultas Ilmu Komputer
        </div>
        <div style="margin-top: 60px;">
            <div class="name">Prof. Dr. Workshop, M.Kom</div>
            <div class="nip">NIP. 198501012010011001</div>
        </div>
    </div>
    
    <div class="footer-note">
        * Undangan ini dibuat secara otomatis oleh sistem Workshop Framework
    </div>
</body>
</html>
