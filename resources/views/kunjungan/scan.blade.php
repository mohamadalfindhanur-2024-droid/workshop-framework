@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h4 class="mb-2">Scan QR Kunjungan</h4>
                <p class="text-muted mb-3">Scan QR toko untuk validasi kunjungan sales.</p>

                <div id="qr-reader" style="min-height: 320px;"></div>

                <div class="mt-3">
                    <button type="button" class="btn btn-primary" id="btn-mulai-scan" onclick="startScanner()">Mulai Scan Kamera</button>
                </div>

                <div id="visit-result" class="mt-3">
                    <div class="alert alert-light border text-muted">Klik "Mulai Scan Kamera" lalu izinkan akses kamera.</div>
                </div>

                <div id="validation-result" class="mt-3"></div>

                <div class="mt-4">
                    <button class="btn btn-outline-primary" onclick="scanLagi()">Scan Lagi</button>
                    <a href="/kunjungan-toko" class="btn btn-light">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let scanner;
let isScanning = true;
let scannerStarted = false;

function hitungJarak(lat1, lng1, lat2, lng2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2)
        + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
        * Math.sin(dLng / 2) * Math.sin(dLng / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

function startScanner() {
    if (scannerStarted) return;

    scanner = new Html5QrcodeScanner(
        'qr-reader',
        { fps: 10, qrbox: 250, rememberLastUsedCamera: true },
        false
    );

    scanner.render(onScanSuccess);

    scannerStarted = true;
    const btn = document.getElementById('btn-mulai-scan');
    if (btn) {
        btn.disabled = true;
        btn.textContent = 'Kamera Aktif';
    }
}

async function onScanSuccess(decodedText) {
    if (!isScanning) return;
    isScanning = false;

    document.getElementById('visit-result').innerHTML = '<div class="alert alert-info">Memuat data toko...</div>';
    document.getElementById('validation-result').innerHTML = '';

    try {
        const tokoRes = await fetch('/api/toko/' + encodeURIComponent(decodedText));
        if (!tokoRes.ok) {
            document.getElementById('visit-result').innerHTML = '<div class="alert alert-danger">QR/Barcode toko tidak ditemukan</div>';
            return;
        }
        const data = await tokoRes.json();

        document.getElementById('visit-result').innerHTML = `
            <div class="card border-0 shadow-sm mt-2">
                <div class="card-body text-start">
                    <h6 class="text-success mb-3">Toko Ditemukan</h6>
                    <table class="table table-borderless mb-0">
                        <tr><th width="35%">Barcode</th><td>${data.barcode}</td></tr>
                        <tr><th>Nama Toko</th><td>${data.nama_toko || '-'}</td></tr>
                        <tr><th>Latitude</th><td>${data.latitude || '-'}</td></tr>
                        <tr><th>Longitude</th><td>${data.longitude || '-'}</td></tr>
                        <tr><th>Accuracy</th><td>${data.accuracy || '-'} m</td></tr>
                    </table>
                </div>
            </div>
        `;

        const pos = await getAccuratePosition(50, 20000);
        const salesLat = pos.coords.latitude;
        const salesLng = pos.coords.longitude;
        const salesAccuracy = pos.coords.accuracy;

        const latToko = parseFloat(data.latitude);
        const lngToko = parseFloat(data.longitude);
        if (Number.isNaN(latToko) || Number.isNaN(lngToko)) {
            document.getElementById('validation-result').innerHTML = '<div class="alert alert-warning">Titik awal toko belum diatur.</div>';
            return;
        }

        const jarak = hitungJarak(latToko, lngToko, salesLat, salesLng);
        const THRESHOLD = 300;
        const accuracyToko = parseFloat(data.accuracy || 0);
        const thresholdEfektif = THRESHOLD + accuracyToko + salesAccuracy;
        const diterima = jarak <= thresholdEfektif;

        document.getElementById('validation-result').innerHTML = `
            <div class="alert alert-${diterima ? 'success' : 'danger'} text-start">
                <h6 class="mb-3">Hasil Validasi Kunjungan</h6>
                <p class="mb-1">Jarak Aktual: <strong>${jarak.toFixed(2)} meter</strong></p>
                <p class="mb-1">Accuracy Toko: <strong>${accuracyToko.toFixed(2)} meter</strong></p>
                <p class="mb-1">Accuracy Sales: <strong>${salesAccuracy.toFixed(2)} meter</strong></p>
                <p class="mb-1">Threshold Dasar: <strong>${THRESHOLD} meter</strong></p>
                <p class="mb-3">Threshold Efektif: <strong>${thresholdEfektif.toFixed(2)} meter</strong></p>
                <h6 class="mb-0">Status: <strong>${diterima ? 'VALID' : 'DI LUAR AREA'}</strong></h6>
            </div>
        `;

        if (scanner) {
            scanner.pause(true);
        }
    } catch (e) {
        document.getElementById('visit-result').innerHTML = `<div class="alert alert-warning">Gagal: ${e.message}</div>`;
    }
}

function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        if (!('geolocation' in navigator)) return reject(new Error('Geolocation not supported'));
        let bestResult = null;
        const startTime = Date.now();
        const watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;
                if (!bestResult || acc < bestResult.coords.accuracy) bestResult = position;
                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }
                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    if (bestResult) resolve(bestResult);
                    else reject(new Error('Timeout, tidak dapat posisi'));
                }
            },
            (error) => reject(error),
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}

function scanLagi() {
    isScanning = true;
    scannerStarted = false;
    document.getElementById('visit-result').innerHTML = '<div class="alert alert-light border text-muted">Arahkan kamera ke QR Code toko</div>';
    document.getElementById('validation-result').innerHTML = '';
    if (scanner) {
        scanner.clear().then(() => {
            scanner = null;
        }).catch(() => {
            scanner = null;
        });
    }
    const btn = document.getElementById('btn-mulai-scan');
    if (btn) {
        btn.disabled = false;
        btn.textContent = 'Mulai Scan Kamera';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('visit-result').innerHTML = '<div class="alert alert-light border text-muted">Izinkan akses kamera saat browser meminta, lalu arahkan ke QR Code toko.</div>';
});
</script>
@endsection
