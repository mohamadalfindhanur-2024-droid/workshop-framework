<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label Harga Barang - TnJ 108</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 5mm 3mm;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .page {
            page-break-after: always;
        }
        
        .page:last-child {
            page-break-after: avoid;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }
        
        td {
            width: 20%;
            height: 20mm;
            border: 1px solid #ddd;
            padding: 1.5mm 2mm;
            vertical-align: middle;
            text-align: center;
        }
        
        .label-header {
            font-size: 6pt;
            font-weight: bold;
            color: #666;
            margin: 0 0 1mm 0;
        }
        
        .nama-barang {
            font-size: 8pt;
            font-weight: bold;
            color: #000;
            margin: 1mm 0;
            line-height: 1.2;
        }
        
        .harga-barang {
            font-size: 10pt;
            font-weight: bold;
            color: #000;
            background-color: #ffeb3b;
            border: 1.5px solid #000;
            padding: 1mm 2mm;
            margin: 1mm 0;
            display: inline-block;
        }
        
        .id-barang {
            font-size: 5pt;
            color: #888;
            margin: 1mm 0 0 0;
        }
    </style>
</head>
<body>
    @php
        // 40 label per halaman (5 kolom x 8 baris)
        $labelsPerSheet = 40;
        
        // Hitung jumlah cell kosong sebelum data (berdasarkan koordinat X dan Y)
        // X = kolom (1-5), Y = baris (1-8)
        $startX = $koordinatX ?? 1;
        $startY = $koordinatY ?? 1;
        $skipCells = ($startY - 1) * 5 + ($startX - 1);
        
        // Gabungkan cell kosong + data barang
        $allItems = [];
        
        // Tambahkan cell kosong dulu sesuai koordinat awal
        for($i = 0; $i < $skipCells; $i++) {
            $allItems[] = null;
        }
        
        // Tambahkan semua data barang
        foreach($barangDipilih as $item) {
            $allItems[] = $item;
        }
        
        // Chunk menjadi halaman-halaman (40 label per halaman)
        $sheets = array_chunk($allItems, $labelsPerSheet);
    @endphp
    
    @foreach($sheets as $sheetIndex => $sheet)
        <div class="page">
            <table>
                @php
                    // Pad to 40 items per sheet
                    while(count($sheet) < $labelsPerSheet) {
                        $sheet[] = null;
                    }
                @endphp
                
                @for($row = 0; $row < 8; $row++)
                    <tr>
                        @for($col = 0; $col < 5; $col++)
                            @php
                                $index = $row * 5 + $col;
                                $item = $sheet[$index] ?? null;
                            @endphp
                            <td>
                                @if($item)
                                    <div class="label-header">UMKM</div>
                                    <div class="nama-barang">{{ $item->nama }}</div>
                                    <div class="harga-barang">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </div>
                                    <div class="id-barang">{{ $item->id_barang }}</div>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endfor
            </table>
        </div>
    @endforeach
</body>
</html>
