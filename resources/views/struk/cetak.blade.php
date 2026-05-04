@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Receipt Container -->
            <div id="struk-container" class="struk-receipt">
                <!-- Header -->
                <div class="struk-header text-center">
                    <h3 class="mb-1">BUKTI PEMBELIAN</h3>
                    <p class="mb-3 small text-muted">Workshop Framework Store</p>
                </div>

                <!-- Transaction Details -->
                <div class="struk-details">
                    <table class="w-100 small">
                        <tr>
                            <td class="fw-bold">Order ID</td>
                            <td class="text-end">{{ $transaksi->kode_transaksi }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal</td>
                            <td class="text-end">{{ $transaksi->tanggal ? $transaksi->tanggal->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Metode</td>
                            <td class="text-end">
                                @if($transaksi->metode_pembayaran === 'qris')
                                    QRIS
                                @elseif($transaksi->metode_pembayaran === 'va')
                                    VA ({{ $transaksi->bank_va }})
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status</td>
                            <td class="text-end">
                                <span class="badge bg-success">{{ strtoupper($transaksi->status_order) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr class="my-2">

                <!-- Items List -->
                <div class="struk-items">
                    <table class="w-100 small">
                        <thead>
                            <tr>
                                <th class="text-start">Item</th>
                                <th class="text-center" style="width: 50px;">Qty</th>
                                <th class="text-end" style="width: 80px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi->detail as $item)
                                <tr>
                                    <td class="text-start">
                                        <small>{{ $item->nama_barang }}</small>
                                        <br>
                                        <small class="text-muted">Rp {{ number_format($item->harga, 0, ',', '.') }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->jumlah }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted small">Tidak ada item</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <hr class="my-2">

                <!-- Totals -->
                <div class="struk-totals">
                    <table class="w-100 small">
                        <tr>
                            <td class="fw-bold">Subtotal</td>
                            <td class="text-end">Rp {{ number_format($transaksi->total - 2500, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Biaya Layanan</td>
                            <td class="text-end">Rp 2.500</td>
                        </tr>
                        <tr class="fw-bold">
                            <td>TOTAL</td>
                            <td class="text-end">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <hr class="my-2">

                <!-- QR Code Barcode -->
                <div class="struk-barcode text-center py-3">
                    <p class="small mb-2">QR Pesanan (Scan untuk verifikasi)</p>
                    <img id="barcode-image" alt="QR Pesanan" class="img-fluid border rounded p-2" style="max-width: 150px;">
                    <p class="small text-muted mt-2">ID: {{ $transaksi->id }}</p>
                </div>

                <hr class="my-2">

                <!-- Footer -->
                <div class="struk-footer text-center">
                    <p class="small mb-0">Terima kasih atas pembelian Anda!</p>
                    <p class="small text-muted">{{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 d-flex gap-2 justify-content-center no-print">
                <button class="btn btn-gradient-primary" onclick="window.print()">
                    <i class="mdi mdi-printer"></i> Cetak
                </button>
                <a href="{{ route('checkout.marketplace') }}" class="btn btn-gradient-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .struk-receipt {
        background: white;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        min-height: 400px;
    }

    .struk-header {
        margin-bottom: 15px;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
    }

    .struk-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .struk-details, .struk-items, .struk-totals {
        margin: 10px 0;
    }

    .struk-details table, .struk-items table, .struk-totals table {
        width: 100%;
        border-collapse: collapse;
    }

    .struk-details tr, .struk-items tbody tr {
        border-bottom: 1px dotted #ccc;
    }

    .struk-totals tr:last-child {
        border-bottom: 2px solid #333;
    }

    .struk-details td, .struk-items td, .struk-totals td {
        padding: 4px 0;
        font-size: 12px;
    }

    .struk-barcode {
        border: 1px dashed #ccc;
        border-radius: 4px;
    }

    .struk-footer {
        font-size: 11px;
        margin-top: 10px;
    }

    @media print {
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .container {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
        }

        .row {
            margin: 0 !important;
        }

        .col-md-6 {
            max-width: 100% !important;
            flex: 0 0 100% !important;
            padding: 0 !important;
        }

        .struk-receipt {
            border: none !important;
            padding: 10px !important;
            box-shadow: none !important;
        }

        .no-print {
            display: none !important;
        }

        @page {
            size: 80mm 120mm;
            margin: 0;
        }
    }
</style>

<script>
    function playBeep(frequency = 800, duration = 200) {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = frequency;
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration / 1000);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + duration / 1000);
        } catch (e) {
            console.log('Audio not supported');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Play beep saat halaman load
        playBeep(800, 200);
        
        // Load QR code image
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $transaksi->id }}`;
        const qrImage = document.getElementById('barcode-image');
        
        const img = new Image();
        img.onload = function () {
            qrImage.src = qrUrl;
        };
        img.onerror = function () {
            qrImage.alt = 'Gagal memuat QR';
        };
        img.src = qrUrl;

        // Play beep saat tombol print diklik
        document.querySelector('button[onclick="window.print()"]').addEventListener('click', function (e) {
            playBeep(1000, 150);
        });
    });
</script>
@endsection
