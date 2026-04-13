@extends('layouts.admin')

@section('title', 'Customer - Akses Kamera')

@section('style_page')
<style>
    .frame-box {
        border: 1px solid #ced4da;
        padding: 16px;
        border-radius: 8px;
        background: #fff;
    }

    .snapshot-box {
        width: 140px;
        height: 140px;
        border: 2px solid #b7d8a8;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8faf8;
    }

    .snapshot-box img,
    #camera-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .status-pill {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-paid { background: #d1e7dd; color: #0f5132; }
    .status-expired { background: #f8d7da; color: #842029; }
    .status-failed { background: #f8d7da; color: #842029; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account"></i>
        </span> Customer
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Customer</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Customer</h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('customer.store') }}">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="nama" class="form-control" placeholder="Nama" required>
                    </div>
                    <div class="mb-2">
                        <textarea name="alamat" class="form-control" placeholder="Alamat" rows="2" required></textarea>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="provinsi" class="form-control" placeholder="Provinsi" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="kota" class="form-control" placeholder="Kota" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="kecamatan" class="form-control" placeholder="Kecamatan" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="kodepos_kelurahan" class="form-control" placeholder="Kodepos - kelurahan" required>
                    </div>

                    <input type="hidden" name="foto_base64" id="foto_base64">
                    <input type="hidden" name="qr_order_id" id="qr_order_id">

                    <div class="d-flex align-items-end gap-3 mt-3">
                        <div>
                            <label class="form-label">Foto</label>
                            <div class="snapshot-box" id="form-photo-box">Foto</div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cameraModal">Ambil Foto</button>
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Validasi QR Pesanan</h4>
                <p class="card-description">Scan QR ID pesanan dari halaman checkout setelah status paid.</p>

                <div id="qr-reader" class="frame-box"></div>

                <div class="mt-3">
                    <p class="mb-2"><strong>Hasil Scan:</strong> <span id="scan-value">-</span></p>
                    <p class="mb-2">Status Pesanan: <span id="scan-status" class="status-pill status-pending">MENUNGGU</span></p>
                    <p class="mb-0">Order: <span id="scan-order-kode">-</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal ambil foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Video</label>
                        <div class="snapshot-box" style="width:100%;height:240px;">
                            <video id="camera-video" autoplay playsinline muted></video>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Snapshot</label>
                        <div class="snapshot-box" style="width:100%;height:240px;" id="snapshot-result">Snapshot</div>
                    </div>
                </div>

                <canvas id="camera-canvas" class="d-none"></canvas>

                <div class="mt-3 d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-secondary" id="btn-start-camera">Pilih kamera</button>
                    <button type="button" class="btn btn-primary" id="btn-take-photo">Ambil Foto</button>
                    <button type="button" class="btn btn-success" id="btn-save-photo" data-bs-dismiss="modal">Simpan Foto</button>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($customers) && $customers->count())
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Customer Terakhir</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kota</th>
                                <th>Kecamatan</th>
                                <th>QR Order</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kota }}</td>
                                <td>{{ $item->kecamatan }}</td>
                                <td>{{ $item->qr_order_id ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('javascript_page')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let qrScanner;
let mediaStream = null;
let capturedPhotoBase64 = '';

function setStatusPill(status) {
    const el = document.getElementById('scan-status');
    const value = String(status || 'pending').toLowerCase();
    let cls = 'status-pending';
    if (value === 'paid') cls = 'status-paid';
    if (value === 'expired') cls = 'status-expired';
    if (value === 'failed') cls = 'status-failed';
    el.className = 'status-pill ' + cls;
    el.textContent = value.toUpperCase();
}

function normalizeOrderId(decodedText) {
    const digits = String(decodedText).match(/\d+/g);
    return digits && digits.length ? digits.join('') : null;
}

function verifyOrder(orderId) {
    fetch('{{ route('customer.verify-order') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ qr_order_id: orderId }),
    })
    .then(async (res) => {
        const body = await res.json();
        if (!res.ok) throw new Error(body.message || 'Order tidak ditemukan');
        return body;
    })
    .then((result) => {
        const data = result.data;
        document.getElementById('scan-value').textContent = orderId;
        document.getElementById('scan-order-kode').textContent = data.kode_transaksi || ('ID-' + data.id);
        document.getElementById('qr_order_id').value = data.id;
        setStatusPill(data.status_order);
    })
    .catch(() => {
        document.getElementById('scan-value').textContent = orderId || '-';
        document.getElementById('scan-order-kode').textContent = '-';
        setStatusPill('failed');
    });
}

function onScanSuccess(decodedText) {
    const orderId = normalizeOrderId(decodedText);
    if (!orderId) {
        setStatusPill('failed');
        return;
    }
    document.getElementById('scan-value').textContent = orderId;
    setStatusPill('pending');
    verifyOrder(orderId);
}

function initQrScanner() {
    qrScanner = new Html5QrcodeScanner('qr-reader', {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        rememberLastUsedCamera: true,
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
    }, false);

    qrScanner.render(onScanSuccess, function () {});
}

async function startCamera() {
    const video = document.getElementById('camera-video');
    if (mediaStream) {
        mediaStream.getTracks().forEach((track) => track.stop());
    }

    mediaStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
    video.srcObject = mediaStream;
}

function takePhoto() {
    const video = document.getElementById('camera-video');
    const canvas = document.getElementById('camera-canvas');
    const snapshot = document.getElementById('snapshot-result');
    const formPhotoBox = document.getElementById('form-photo-box');

    if (!video.videoWidth || !video.videoHeight) {
        return;
    }

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    capturedPhotoBase64 = canvas.toDataURL('image/jpeg', 0.9);
    document.getElementById('foto_base64').value = capturedPhotoBase64;

    const img1 = document.createElement('img');
    img1.src = capturedPhotoBase64;
    snapshot.innerHTML = '';
    snapshot.appendChild(img1);

    const img2 = document.createElement('img');
    img2.src = capturedPhotoBase64;
    formPhotoBox.innerHTML = '';
    formPhotoBox.appendChild(img2);
}

document.getElementById('btn-start-camera').addEventListener('click', function () {
    startCamera().catch(() => alert('Tidak bisa mengakses kamera. Pastikan izin kamera aktif.'));
});

document.getElementById('btn-take-photo').addEventListener('click', takePhoto);

document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function () {
    if (mediaStream) {
        mediaStream.getTracks().forEach((track) => track.stop());
        mediaStream = null;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    initQrScanner();
});
</script>
@endsection
