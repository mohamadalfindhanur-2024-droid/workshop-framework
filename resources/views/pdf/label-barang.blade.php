<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  @page {
      size: 210mm 167mm;
      margin: 0;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
      margin: 0;
      width: 210mm;
      height: 167mm;
      font-family: DejaVu Sans, Arial, sans-serif;
  }

  table {
      border-collapse: separate;
      border-spacing: 2mm 2mm;
      margin: 0 auto;
  }

  td {
      width: 43mm;
      height: 28mm;
      background: #ffffff;
      border: 0.3px solid #ffffff;
      border-radius: 4px;
      text-align: center;
      vertical-align: middle;
      overflow: hidden;
      padding: 0.5mm;
  }

  td.empty {
      background: transparent;
      border: none;
  }

  .nama {
      font-size: 7pt;
      font-weight: bold;
      line-height: 1.1;
      margin-top: 0.4mm;
  }

  .kode {
      font-size: 6pt;
      color: #888;
  }

  .harga {
      font-size: 9pt;
      font-weight: bold;
      color: #198754;
  }

  .barcode {
      margin-top: 1mm;
      height: 15mm;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 2.5mm;
  }

  .barcode img {
      width: 100%;
      height: 100%;
      display: block;
  }

  .footer {
      font-size: 5pt;
      color: #aaa;
      letter-spacing: 0.5px;
  }

  .page-break {
      page-break-after: always;
  }
</style>
</head>
<body>

@php
    $cols      = 5;
    $rows      = 8;
    $totalSlot = $cols * $rows;
    
    // Hitung start slot dari koordinat X,Y
    $startX = $koordinatX ?? 1;
    $startY = $koordinatY ?? 1;
    $startSlot = ($startY - 1) * $cols + ($startX - 1);
    
    $items     = collect($barangDipilih)->values();
    $itemCount = $items->count();
    $itemIdx   = 0;
    $firstPageStart = $startSlot;
    $pageNum   = 0;
@endphp

@php
    $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorSVG();
@endphp

@while($itemIdx < $itemCount)
    @php
        $pageNum++;
        $slotStart = ($pageNum === 1) ? $firstPageStart : 0;
    @endphp

    <div class="{{ $pageNum > 1 ? 'page-break' : '' }}">
        <table>
            @for($row = 0; $row < $rows; $row++)
                <tr>
                    @for($col = 0; $col < $cols; $col++)
                        @php $slot = $row * $cols + $col; @endphp

                        @if($slot < $slotStart || $itemIdx >= $itemCount)
                            <td class="empty"></td>
                        @else
                            @php $item = $items[$itemIdx]; $itemIdx++; @endphp
                            @php
                                $barcodeSvg = $barcodeGenerator->getBarcode($item->id_barang, $barcodeGenerator::TYPE_CODE_128, 5, 80);
                                $barcodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($barcodeSvg);
                            @endphp
                            <td>
                                <div class="kode">{{ $item->id_barang }}</div>
                                <div class="nama">
                                    {{ mb_strlen($item->nama) > 20 ? mb_substr($item->nama, 0, 20).'…' : $item->nama }}
                                </div>
                                <div class="barcode">
                                    <img src="{{ $barcodeDataUri }}" alt="Barcode {{ $item->id_barang }}">
                                </div>
                                <div class="harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                            </td>
                        @endif

                    @endfor
                </tr>
            @endfor
        </table>
    </div>

@endwhile

</body>
</html>