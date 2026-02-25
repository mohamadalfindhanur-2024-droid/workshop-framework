<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Kategori</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #2196F3;
            color: white;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA KATEGORI</h1>
        <p>Sistem Informasi Perpustakaan</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    @if($kategoris->count() > 0)
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Kategori</th>
                <th width="50%">Deskripsi</th>
                <th width="15%">Jumlah Buku</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategoris as $index => $kategori)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $kategori->nama_kategori }}</td>
                <td>{{ $kategori->deskripsi ?? '-' }}</td>
                <td style="text-align: center;"><strong>{{ $kategori->buku_count }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p><strong>Total Kategori: {{ $kategoris->count() }}</strong></p>
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>
    @else
    <div class="no-data">
        <p>Tidak ada data kategori yang tersedia.</p>
    </div>
    @endif
</body>
</html>
