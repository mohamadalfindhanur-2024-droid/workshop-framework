@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h4 class="mb-1">Tambah Toko</h4>
        <p class="text-muted mb-4">Tambahkan lokasi toko untuk validasi kunjungan sales.</p>

        <form action="/tambah-toko" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Barcode</label>
                <input type="text" name="barcode" class="form-control" placeholder="Masukkan barcode toko" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Toko</label>
                <input type="text" name="nama_toko" class="form-control" placeholder="Masukkan nama toko" required>
            </div>

            <div class="mb-3">
                <button type="button" id="btn-lokasi" class="btn btn-outline-primary" onclick="ambilLokasi()">
                    <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi
                </button>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" readonly required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" id="longitude" name="longitude" class="form-control" readonly required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Accuracy (m)</label>
                    <input type="text" id="accuracy" name="accuracy" class="form-control" readonly required>
                </div>
            </div>

            <div class="mt-2">
                <button type="submit" class="btn btn-success">Simpan Toko</button>
                <a href="/kunjungan-toko" class="btn btn-light">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('javascript_page')
<script>
async function ambilLokasi() {
    const tombol = document.getElementById('btn-lokasi');
    tombol.disabled = true;
    tombol.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mengambil Lokasi...';

    try {
        const position = await getAccuratePosition(30);
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;
        document.getElementById('accuracy').value = Math.round(position.coords.accuracy);
        tombol.innerHTML = '<i class="mdi mdi-check-circle"></i> Lokasi Berhasil';
    } catch (error) {
        alert('Gagal mengambil lokasi: ' + error.message);
        tombol.innerHTML = '<i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi';
        tombol.disabled = false;
    }
}

function getAccuratePosition(targetAccuracy = 30, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        if (!('geolocation' in navigator)) {
            reject(new Error('Geolocation tidak didukung browser ini'));
            return;
        }

        let bestResult = null;
        const startTime = Date.now();

        const watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;

                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                }

                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                    return;
                }

                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    if (bestResult) {
                        resolve(bestResult);
                    } else {
                        reject(new Error('Timeout, tidak dapat posisi'));
                    }
                }
            },
            (error) => reject(error),
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}
</script>
@endsection
