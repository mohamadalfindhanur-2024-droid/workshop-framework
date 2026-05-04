@extends('layouts.admin')

@section('title', 'Scan QR Pesanan Customer')

@section('style_page')
<style>
    .qr-shell {
        border: 1px solid #ececf4;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfbfd 100%);
        box-shadow: 0 18px 50px rgba(93, 63, 211, 0.08);
    }

    .qr-stage {
        min-height: 330px;
        border: 1px solid #ececf4;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
    }

    .detail-box {
        border: 1px solid #ececf4;
        border-radius: 16px;
        background: #fff;
    }

    .badge-soft-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-soft-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-soft-danger {
        background: #fee2e2;
        color: #991b1b;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-warning text-white me-2">
            <i class="mdi mdi-qrcode-scan"></i>
        </span> Scan QR Pesanan Customer
    </h3>
    <div>
        <a href="{{ route('customer.index') }}" class="btn btn-gradient-primary">
            <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Data Pesanan
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card qr-shell h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="mdi mdi-camera-outline me-1"></i> Scan QR Pesanan Customer</span>
                    <span class="badge badge-soft-warning rounded-pill px-3 py-2" id="qr-status-chip">Menunggu Scan</span>
                </div>
                <div class="qr-stage p-3" id="qr-stage">
                    <div class="d-flex align-items-center justify-content-center h-100 text-center text-secondary" id="qr-placeholder">
                        <div>
                            <div class="mb-3"><i class="mdi mdi-qrcode-scan" style="font-size: 3rem;"></i></div>
                            <h5 class="mb-2">Arahkan Kamera ke QR Code</h5>
                            <p class="mb-3">Scan QR code dari halaman pengajuan customer.</p>
                            <button type="button" class="btn btn-warning text-dark" id="btn-mulai-scan"><i class="mdi mdi-play"></i> Scan Ulang</button>
                        </div>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0">Gunakan browser yang sudah memberi izin kamera.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card detail-box h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-secondary rounded-pill px-3 py-2"><i class="mdi mdi-file-document-outline me-1"></i> Detail Pesanan</span>
                    <span class="badge badge-soft-warning rounded-pill px-3 py-2" id="payment-status-chip">Belum Ada QR Code yang Di-scan</span>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted" style="width: 160px;">ID Pesanan</td>
                                <td><strong id="detail-id-pesanan">-</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Waktu Dibuat</td>
                                <td><span id="detail-waktu">-</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Nominal</td>
                                <td><span class="fw-bold text-success" id="detail-total">-</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status Bayar</td>
                                <td><span class="badge badge-soft-warning rounded-pill px-3 py-2" id="detail-status">BELUM ADA QR CODE</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border rounded-3 p-3 mb-3" style="background:#fbfcff;">
                    <div class="text-muted small mb-2">Kode QR / Order</div>
                    <div class="fw-semibold" id="detail-kode-qr">Belum ada QR code yang dipindai.</div>
                </div>

                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">Daftar Item Pesanan</h6>
                        <span class="text-muted small" id="detail-jumlah-item">0 item</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th width="80">Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detail-items-body">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada data pesanan.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let qrScanner = null;

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

function setStatusChip(text, type) {
    const chip = document.getElementById('payment-status-chip');
    chip.textContent = text;
    chip.className = 'badge rounded-pill px-3 py-2 ' + type;
}

function setDetailStatus(status) {
    const chip = document.getElementById('detail-status');
    const value = String(status || 'pending').toLowerCase();

    if (value === 'paid') {
        chip.className = 'badge badge-soft-success rounded-pill px-3 py-2';
        chip.textContent = 'LUNAS';
        setStatusChip('Pembayaran LUNAS', 'badge-soft-success');
        return;
    }

    if (value === 'expired') {
        chip.className = 'badge badge-soft-danger rounded-pill px-3 py-2';
        chip.textContent = 'EXPIRED';
        setStatusChip('Pesanan Expired', 'badge-soft-danger');
        return;
    }

    chip.className = 'badge badge-soft-warning rounded-pill px-3 py-2';
    chip.textContent = value.toUpperCase();
    setStatusChip('Menunggu Pembayaran', 'badge-soft-warning');
}

function formatCurrency(value) {
    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
}

function renderItems(items) {
    const tbody = document.getElementById('detail-items-body');
    const list = Array.isArray(items) ? items : [];

    if (!list.length) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Belum ada data pesanan.</td></tr>';
        document.getElementById('detail-jumlah-item').textContent = '0 item';
        return;
    }

    document.getElementById('detail-jumlah-item').textContent = list.length + ' item';
    tbody.innerHTML = list.map((item) => `
        <tr>
            <td>${item.nama_barang}</td>
            <td class="text-center">${item.jumlah}</td>
            <td>${formatCurrency(item.harga)}</td>
            <td>${formatCurrency(item.subtotal)}</td>
        </tr>
    `).join('');
}

function resetDetail() {
    document.getElementById('detail-id-pesanan').textContent = '-';
    document.getElementById('detail-waktu').textContent = '-';
    document.getElementById('detail-total').textContent = '-';
    document.getElementById('detail-kode-qr').textContent = 'Belum ada QR code yang dipindai.';
    document.getElementById('detail-items-body').innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Belum ada data pesanan.</td></tr>';
    document.getElementById('detail-jumlah-item').textContent = '0 item';
    setDetailStatus('pending');
    setStatusChip('Belum Ada QR Code yang Di-scan', 'badge-soft-warning');
}

async function verifyQr(qrOrderId) {
    const response = await fetch('{{ route('customer.verify-order') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ qr_order_id: qrOrderId }),
    });

    return await response.json();
}

async function handleScan(decodedText) {
    const orderId = String(decodedText || '').match(/\d+/g);
    const qrOrderId = orderId ? orderId.join('') : null;

    if (!qrOrderId) {
        setStatusChip('QR Tidak Valid', 'badge-soft-danger');
        playBeep(600, 150);
        return;
    }

    document.getElementById('detail-kode-qr').textContent = qrOrderId;

    try {
        const result = await verifyQr(qrOrderId);
        if (result.status !== 'success') {
            setStatusChip('Order Tidak Ditemukan', 'badge-soft-danger');
            setDetailStatus('pending');
            playBeep(600, 150);
            if (qrScanner) qrScanner.clear();
            return;
        }

        const data = result.data;
        document.getElementById('detail-id-pesanan').textContent = data.kode_transaksi || ('ID-' + data.id);
        document.getElementById('detail-waktu').textContent = data.tanggal || '-';
        document.getElementById('detail-total').textContent = formatCurrency(data.total);
        document.getElementById('detail-kode-qr').textContent = qrOrderId;
        setDetailStatus(data.status_order);
        renderItems(data.items || []);
        
        playBeep(800, 200);
        
        if (qrScanner) qrScanner.clear();
    } catch (error) {
        setStatusChip('Gagal Memverifikasi', 'badge-soft-danger');
        playBeep(600, 150);
        if (qrScanner) qrScanner.clear();
    }
}

async function startScanner() {
    const stage = document.getElementById('qr-stage');
    resetDetail();

    if (!('Html5QrcodeScanner' in window)) {
        stage.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-center text-secondary p-4">Html5Qrcode tidak tersedia.</div>';
        setStatusChip('Browser Tidak Mendukung', 'badge-soft-danger');
        return;
    }

    stage.innerHTML = '<div id="qr-reader" class="w-100 h-100"></div>';
    qrScanner = new Html5QrcodeScanner('qr-reader', {
        fps: 10,
        qrbox: { width: 280, height: 280 },
        rememberLastUsedCamera: true,
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
    }, false);

    qrScanner.render(async function (decodedText) {
        await handleScan(decodedText);
    }, function () {});
    setStatusChip('Scan Berjalan', 'badge-soft-warning');
}

document.getElementById('btn-mulai-scan').addEventListener('click', startScanner);

document.addEventListener('DOMContentLoaded', function () {
    resetDetail();
});
</script>
@endsection