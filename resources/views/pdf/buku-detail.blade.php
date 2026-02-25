<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Buku - {{ $buku->judul }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .content {
            margin: 20px 0;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table tr {
            border-bottom: 1px solid #eee;
        }
        .detail-table td {
            padding: 15px 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #333;
            width: 200px;
            background-color: #f5f5f5;
        }
        .detail-value {
            color: #666;
            padding-left: 20px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL BUKU</h1>
        <p>Sistem Informasi Perpustakaan</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <div class="content">
        <table class="detail-table">
            <tr>
                <td class="detail-label">Kode Buku</td>
                <td class="detail-value">{{ $buku->kode }}</td>
            </tr>
            <tr>
                <td class="detail-label">Judul Buku</td>
                <td class="detail-value">{{ $buku->judul }}</td>
            </tr>
            <tr>
                <td class="detail-label">Pengarang</td>
                <td class="detail-value">{{ $buku->pengarang }}</td>
            </tr>
            <tr>
                <td class="detail-label">Penerbit</td>
                <td class="detail-value">{{ $buku->penerbit }}</td>
            </tr>
            <tr>
                <td class="detail-label">Kategori</td>
                <td class="detail-value">{{ $buku->kategori->nama_kategori ?? '-' }}</td>
            </tr>
            @if($buku->kategori && $buku->kategori->deskripsi)
            <tr>
                <td class="detail-label">Deskripsi Kategori</td>
                <td class="detail-value">{{ $buku->kategori->deskripsi }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
