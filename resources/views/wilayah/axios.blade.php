@extends('layouts.admin')

@section('title', 'Wilayah - Axios')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-map-marker-multiple"></i>
        </span> Wilayah Indonesia (Axios)
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Wilayah Axios</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Pilih Wilayah <span class="badge badge-gradient-success">Axios</span></h4>
            </div>
            <div class="card-body">

                {{-- Provinsi --}}
                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label font-weight-bold">Provinsi :</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="ax-provinsi">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinsi as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Kota --}}
                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label font-weight-bold">Kota :</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="ax-kota" disabled>
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>
                </div>

                {{-- Kecamatan --}}
                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label font-weight-bold">Kecamatan :</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="ax-kecamatan" disabled>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                </div>

                {{-- Kelurahan --}}
                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label font-weight-bold">Kelurahan :</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="ax-kelurahan" disabled>
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                    </div>
                </div>

                {{-- Hasil --}}
                <div class="mt-4 p-3 bg-light rounded" id="ax-hasil" style="display:none;">
                    <h6 class="font-weight-bold text-success mb-2"><i class="mdi mdi-map-marker-check"></i> Wilayah Terpilih:</h6>
                    <table class="table table-sm mb-0">
                        <tr><td class="font-weight-bold" width="120">Provinsi</td><td>: <span id="ax-hasil-provinsi">-</span></td></tr>
                        <tr><td class="font-weight-bold">Kota</td><td>: <span id="ax-hasil-kota">-</span></td></tr>
                        <tr><td class="font-weight-bold">Kecamatan</td><td>: <span id="ax-hasil-kecamatan">-</span></td></tr>
                        <tr><td class="font-weight-bold">Kelurahan</td><td>: <span id="ax-hasil-kelurahan">-</span></td></tr>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Info Request</h4>
            </div>
            <div class="card-body">
                <small class="text-muted">Log Axios request akan tampil di console browser (F12 → Console)</small>
                <div id="ax-log" class="mt-3" style="font-size:12px; max-height:300px; overflow-y:auto;">
                    <div class="text-muted"><i>Belum ada request...</i></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
{{-- Axios CDN --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Set CSRF token default header untuk semua request Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function addLog(message, type) {
        const color = type === 'success' ? 'text-success' : (type === 'error' ? 'text-danger' : 'text-info');
        const time  = new Date().toLocaleTimeString('id-ID');
        const log   = document.getElementById('ax-log');
        log.insertAdjacentHTML('afterbegin', `<div class="${color}">[${time}] ${message}</div>`);
    }

    function resetSelect(id, label) {
        const el = document.getElementById(id);
        el.innerHTML = `<option value="">-- Pilih ${label} --</option>`;
        el.disabled = true;
    }

    // ==================== PROVINSI CHANGE ====================
    document.getElementById('ax-provinsi').addEventListener('change', function () {
        const idProvinsi    = this.value;
        const namaProvinsi  = this.options[this.selectedIndex].text;

        resetSelect('ax-kota', 'Kota');
        resetSelect('ax-kecamatan', 'Kecamatan');
        resetSelect('ax-kelurahan', 'Kelurahan');
        document.getElementById('ax-hasil').style.display = 'none';

        if (!idProvinsi) return;

        addLog(`GET /wilayah/kota?id_provinsi=${idProvinsi}`, 'info');

        axios.get('{{ route("wilayah.kota") }}', { params: { id_provinsi: idProvinsi } })
            .then(function (response) {
                console.log('Response kota:', response.data);
                const res = response.data;
                if (res.status === 'success') {
                    addLog(`✓ ${res.data.length} kota ditemukan`, 'success');
                    const select = document.getElementById('ax-kota');
                    res.data.forEach(function (kota) {
                        const opt = document.createElement('option');
                        opt.value       = kota.id;
                        opt.textContent = kota.nama;
                        select.appendChild(opt);
                    });
                    select.disabled = false;
                    document.getElementById('ax-hasil-provinsi').textContent = namaProvinsi;
                    ['ax-hasil-kota','ax-hasil-kecamatan','ax-hasil-kelurahan'].forEach(id => {
                        document.getElementById(id).textContent = '-';
                    });
                    document.getElementById('ax-hasil').style.display = 'block';
                }
            })
            .catch(function (error) {
                addLog('✗ Gagal mengambil data kota', 'error');
                console.log(error);
            });
    });

    // ==================== KOTA CHANGE ====================
    document.getElementById('ax-kota').addEventListener('change', function () {
        const idKota    = this.value;
        const namaKota  = this.options[this.selectedIndex].text;

        resetSelect('ax-kecamatan', 'Kecamatan');
        resetSelect('ax-kelurahan', 'Kelurahan');

        if (!idKota) return;

        addLog(`GET /wilayah/kecamatan?id_kota=${idKota}`, 'info');

        axios.get('{{ route("wilayah.kecamatan") }}', { params: { id_kota: idKota } })
            .then(function (response) {
                console.log('Response kecamatan:', response.data);
                const res = response.data;
                if (res.status === 'success') {
                    addLog(`✓ ${res.data.length} kecamatan ditemukan`, 'success');
                    const select = document.getElementById('ax-kecamatan');
                    res.data.forEach(function (kec) {
                        const opt = document.createElement('option');
                        opt.value       = kec.id;
                        opt.textContent = kec.nama;
                        select.appendChild(opt);
                    });
                    select.disabled = false;
                    document.getElementById('ax-hasil-kota').textContent = namaKota;
                    ['ax-hasil-kecamatan','ax-hasil-kelurahan'].forEach(id => {
                        document.getElementById(id).textContent = '-';
                    });
                }
            })
            .catch(function (error) {
                addLog('✗ Gagal mengambil data kecamatan', 'error');
                console.log(error);
            });
    });

    // ==================== KECAMATAN CHANGE ====================
    document.getElementById('ax-kecamatan').addEventListener('change', function () {
        const idKecamatan   = this.value;
        const namaKecamatan = this.options[this.selectedIndex].text;

        resetSelect('ax-kelurahan', 'Kelurahan');

        if (!idKecamatan) return;

        addLog(`GET /wilayah/kelurahan?id_kecamatan=${idKecamatan}`, 'info');

        axios.get('{{ route("wilayah.kelurahan") }}', { params: { id_kecamatan: idKecamatan } })
            .then(function (response) {
                console.log('Response kelurahan:', response.data);
                const res = response.data;
                if (res.status === 'success') {
                    addLog(`✓ ${res.data.length} kelurahan ditemukan`, 'success');
                    const select = document.getElementById('ax-kelurahan');
                    res.data.forEach(function (kel) {
                        const opt = document.createElement('option');
                        opt.value       = kel.id;
                        opt.textContent = kel.nama;
                        select.appendChild(opt);
                    });
                    select.disabled = false;
                    document.getElementById('ax-hasil-kecamatan').textContent = namaKecamatan;
                    document.getElementById('ax-hasil-kelurahan').textContent = '-';
                }
            })
            .catch(function (error) {
                addLog('✗ Gagal mengambil data kelurahan', 'error');
                console.log(error);
            });
    });

    // ==================== KELURAHAN CHANGE ====================
    document.getElementById('ax-kelurahan').addEventListener('change', function () {
        const namaKelurahan = this.options[this.selectedIndex].text;
        if (this.value) {
            document.getElementById('ax-hasil-kelurahan').textContent = namaKelurahan;
            addLog(`✓ Kelurahan dipilih: ${namaKelurahan}`, 'success');
        }
    });
</script>
@endsection
