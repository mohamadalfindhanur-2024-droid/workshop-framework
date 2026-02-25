<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kategori - {{ $kategori->nama_kategori }}</title>
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
        .kategori-info {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 5px;
            border-left: 4px solid #2196F3;
        }
        .kategori-info h2 {
            margin: 0 0 10px 0;
            color: #2196F3;
            font-size: 18px;
        }
        .kategori-info p {
            margin: 5px 0;
            color: #666;
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
        h3 {
            margin-top: 25px;
            color: #333;
            font-size: 16px;
            border-bottom: 2px solid #2196F3;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KATEGORI DAN BUKU</h1>
        <p>Sistem Informasi Perpustakaan</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <div class="kategori-info">
        <h2>{{ $kategori->nama_kategori }}</h2>
        <p><strong>Deskripsi:</strong> {{ $kategori->deskripsi ?? '-' }}</p>
        <p><strong>Jumlah Buku:</strong> {{ $kategori->buku->count() }} buku</p>
    </div>

    <h3>Daftar Buku dalam Kategori</h3>

    @if($kategori->buku->count() > 0)
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th width="35%">Judul Buku</th>
                <th width="25%">Pengarang</th>
                <th width="20%">Penerbit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategori->buku as $index => $buku)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $buku->kode }}</td>
                <td>{{ $buku->judul }}</td>
                <td>{{ $buku->pengarang }}</td>
                <td>{{ $buku->penerbit }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <p>Belum ada buku dalam kategori ini.</p>
    </div>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
