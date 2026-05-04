@extends('layouts.admin')

@section('title', 'Barcode Scanner')

@section('style_page')
<style>
    .scanner-shell {
        border: 1px solid #ececf4;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
        box-shadow: 0 18px 50px rgba(93, 63, 211, 0.08);
    }

    .scanner-stage {
        min-height: 320px;
        border: 1px solid #ececf4;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        position: relative;
    }

    .scanner-video,
    .scanner-stage video,
    .scanner-stage canvas {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
    }

    .scan-result-box {
        border: 1px solid #ececf4;
        border-radius: 16px;
        min-height: 320px;
        background: #fff;
    }

    .scan-value {
        font-size: 1.1rem;
        word-break: break-word;
    }

    .history-card .table > :not(caption) > * > * {
        padding: 0.85rem 1rem;
    }

    .chip-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.7rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .chip-good { background: #d1fae5; color: #065f46; }
    .chip-warn { background: #fef3c7; color: #92400e; }
    .chip-bad { background: #fee2e2; color: #991b1b; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-info text-white me-2">
            <i class="mdi mdi-barcode-scan"></i>
        </span> Barcode Scanner
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Barcode Scanner</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card scanner-shell h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info text-white rounded-pill px-3 py-2"><i class="mdi mdi-camera me-1"></i> Barcode Scanner</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-info" id="btn-scan-ulang"><i class="mdi mdi-refresh"></i> Scan Ulang</button>
                </div>

                <div class="scanner-stage d-flex align-items-center justify-content-center p-3" id="scanner-stage">
                    <div class="text-center text-secondary" id="scanner-placeholder">
                        <div class="mb-3"><i class="mdi mdi-qrcode-scan" style="font-size: 3rem;"></i></div>
                        <h5 class="mb-2">Arahkan Kamera Ke Barcode</h5>
                        <p class="mb-3">Barcode akan dibaca otomatis lalu barang akan tampil di panel hasil scan.</p>
                        <button type="button" class="btn btn-primary" id="btn-scan-ulang-utama"><i class="mdi mdi-play"></i> Scan Ulang</button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mt-3 text-muted small">
                    <i class="mdi mdi-information-outline"></i>
                    <span>Gunakan browser yang mengizinkan akses kamera untuk hasil terbaik.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card scan-result-box h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="mdi mdi-cube-outline me-1"></i> Hasil Scan Barang</span>
                    </div>
                    <span class="chip-status chip-warn" id="barcode-status-chip">Menunggu</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted" style="width: 160px;">ID Barang</td>
                                <td><strong id="hasil-id-barang">-</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Barang</td>
                                <td><span id="hasil-nama-barang">-</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga</td>
                                <td><span id="hasil-harga-barang" class="text-success fw-bold">-</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Waktu Scan</td>
                                <td><span id="hasil-waktu-scan">-</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 p-3 rounded-3" style="background:#f8fafc; border:1px dashed #d7dde8;">
                    <div class="text-muted small mb-1">Kode yang terdeteksi</div>
                    <div class="scan-value fw-semibold" id="hasil-kode-detected">Belum ada barcode yang dipindai.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card history-card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between p-4 pb-2">
                    <div>
                        <h4 class="card-title mb-1"><i class="mdi mdi-history me-1"></i> Riwayat Scan</h4>
                        <p class="text-muted mb-0">Daftar barcode yang terakhir dipindai pada sesi browser ini.</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-clear-history"><i class="mdi mdi-delete-outline"></i> Hapus Riwayat</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody id="scan-history-body">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada scan barcode.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script src="https://unpkg.com/@zxing/library@latest"></script>
<script>
const historyKey = 'barcode_scanner_history_v1';
let codeReader = null;
let isScanning = false;

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

function loadHistory() {
    try {
        return JSON.parse(localStorage.getItem(historyKey) || '[]');
    } catch (error) {
        return [];
    }
}

function saveHistory(history) {
    localStorage.setItem(historyKey, JSON.stringify(history.slice(0, 10)));
}

function formatCurrency(value) {
    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
}

function renderHistory() {
    const tbody = document.getElementById('scan-history-body');
    const history = loadHistory();

    if (!history.length) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada scan barcode.</td></tr>';
        return;
    }

    tbody.innerHTML = history.map((item, index) => `
        <tr>
            <td>${index + 1}</td>
            <td><strong>${item.id_barang}</strong></td>
            <td>${item.nama_barang}</td>
            <td>${formatCurrency(item.harga)}</td>
            <td>${item.waktu_scan}</td>
        </tr>
    `).join('');
}

function setStatus(text, variant) {
    const chip = document.getElementById('barcode-status-chip');
    chip.textContent = text;
    chip.className = 'chip-status ' + variant;
}

function resetResult() {
    document.getElementById('hasil-id-barang').textContent = '-';
    document.getElementById('hasil-nama-barang').textContent = '-';
    document.getElementById('hasil-harga-barang').textContent = '-';
    document.getElementById('hasil-waktu-scan').textContent = '-';
    document.getElementById('hasil-kode-detected').textContent = 'Belum ada barcode yang dipindai.';
    setStatus('Menunggu', 'chip-warn');
}

async function stopScanner() {
    isScanning = false;

    if (codeReader) {
        try {
            await codeReader.reset();
        } catch (error) {
            console.log(error);
        }
    }
}

async function fetchBarang(kode) {
    const response = await fetch('{{ route('kasir.cari-barang') }}?kode=' + encodeURIComponent(kode));
    return await response.json();
}

async function handleDetectedBarcode(decodedText) {
    if (!isScanning) {
        return;
    }

    const kode = String(decodedText || '').trim();
    if (!kode) {
        return;
    }

    isScanning = false;
    await stopScanner();
    playBeep(800, 200);

    try {
        const res = await fetchBarang(kode);
        const waktu = new Date().toLocaleString('id-ID');

        if (res.status !== 'success') {
            setStatus('Tidak Ditemukan', 'chip-bad');
            document.getElementById('hasil-kode-detected').textContent = kode;
            document.getElementById('hasil-waktu-scan').textContent = waktu;
            document.getElementById('hasil-id-barang').textContent = kode;
            playBeep(600, 150);
            return;
        }

        const data = res.data;
        document.getElementById('hasil-id-barang').textContent = data.id_barang;
        document.getElementById('hasil-nama-barang').textContent = data.nama;
        document.getElementById('hasil-harga-barang').textContent = formatCurrency(data.harga);
        document.getElementById('hasil-waktu-scan').textContent = waktu;
        document.getElementById('hasil-kode-detected').textContent = kode;
        setStatus('Terdeteksi', 'chip-good');

        const history = loadHistory();
        history.unshift({
            id_barang: data.id_barang,
            nama_barang: data.nama,
            harga: data.harga,
            waktu_scan: waktu,
        });
        saveHistory(history);
        renderHistory();
    } catch (error) {
        setStatus('Gagal', 'chip-bad');
        document.getElementById('hasil-kode-detected').textContent = kode;
        playBeep(600, 150);
    }
}

async function startScanner() {
    const stage = document.getElementById('scanner-stage');
    resetResult();

    if (!window.ZXing || !window.ZXing.BrowserMultiFormatReader) {
        stage.innerHTML = `
            <div class="text-center text-secondary p-4">
                <div class="mb-3"><i class="mdi mdi-alert-circle-outline" style="font-size: 3rem;"></i></div>
                <h5>ZXing.js tidak berhasil dimuat</h5>
                <p class="mb-3">Periksa koneksi internet atau buka kembali halaman ini.</p>
            </div>
        `;
        setStatus('Tidak Didukung', 'chip-bad');
        return;
    }

    stage.innerHTML = '<video id="scanner-video" class="scanner-video" autoplay playsinline muted></video>';
    const video = document.getElementById('scanner-video');

    codeReader = new ZXing.BrowserMultiFormatReader();
    isScanning = true;
    setStatus('Mencari Barcode', 'chip-warn');

    try {
        const devices = await codeReader.listVideoInputDevices();
        const selectedDeviceId = devices.length ? devices[0].deviceId : null;

        await codeReader.decodeFromVideoDevice(selectedDeviceId, video, async (result, err) => {
            if (!isScanning) {
                return;
            }

            if (result && typeof result.getText === 'function') {
                await handleDetectedBarcode(result.getText());
                return;
            }

            if (err && !(err instanceof ZXing.NotFoundException)) {
                console.log(err);
            }
        });
    } catch (error) {
        setStatus('Gagal', 'chip-bad');
        stage.innerHTML = `
            <div class="text-center text-secondary p-4">
                <div class="mb-3"><i class="mdi mdi-camera-off" style="font-size: 3rem;"></i></div>
                <h5>Kamera tidak bisa dibuka</h5>
                <p class="mb-3">Pastikan browser memberi izin kamera dan gunakan HTTPS atau localhost.</p>
            </div>
        `;
    }
}

document.getElementById('btn-scan-ulang').addEventListener('click', async function () {
    await stopScanner();
    document.getElementById('scanner-stage').innerHTML = `
        <div class="text-center text-secondary" id="scanner-placeholder">
            <div class="mb-3"><i class="mdi mdi-qrcode-scan" style="font-size: 3rem;"></i></div>
            <h5 class="mb-2">Arahkan Kamera Ke Barcode</h5>
            <p class="mb-3">Barcode akan dibaca otomatis lalu barang akan tampil di panel hasil scan.</p>
            <button type="button" class="btn btn-primary" id="btn-scan-ulang-utama"><i class="mdi mdi-play"></i> Scan Ulang</button>
        </div>
    `;
    document.getElementById('btn-scan-ulang-utama').addEventListener('click', async () => {
        document.getElementById('scanner-stage').innerHTML = '<div class="text-center text-secondary p-4">Memulai kamera...</div>';
        await startScanner();
    });
    resetResult();
});

document.getElementById('btn-scan-ulang-utama').addEventListener('click', async () => {
    document.getElementById('scanner-stage').innerHTML = '<div class="text-center text-secondary p-4">Memulai kamera...</div>';
    await startScanner();
});

document.getElementById('btn-clear-history').addEventListener('click', function () {
    localStorage.removeItem(historyKey);
    renderHistory();
});

document.addEventListener('DOMContentLoaded', function () {
    renderHistory();
    resetResult();
});
</script>
@endsection