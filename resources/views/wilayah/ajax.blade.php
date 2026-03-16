@extends('layouts.admin')

@section('title', 'Wilayah - Ajax jQuery')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker-multiple"></i>
        </span> Wilayah Indonesia (Ajax jQuery)
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Wilayah Ajax</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Pilih Wilayah <span class="badge badge-gradient-primary">Ajax jQuery</span></h4>
            </div>
            <div class="card-body">

                {{-- Provinsi --}}
                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label font-weight-bold">Provinsi :</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="select-provinsi">
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
                        <select class="form-control" id="select-kota" disabled>
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>
                </div>

                {{-- Kecamatan --}}
                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label font-weight-bold">Kecamatan :</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="select-kecamatan" disabled>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                </div>

                {{-- Kelurahan --}}
                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label font-weight-bold">Kelurahan :</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="select-kelurahan" disabled>
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                    </div>
                </div>

                {{-- Hasil --}}
                <div class="mt-4 p-3 bg-light rounded" id="hasil-wilayah" style="display:none;">
                    <h6 class="font-weight-bold text-primary mb-2"><i class="mdi mdi-map-marker-check"></i> Wilayah Terpilih:</h6>
                    <table class="table table-sm mb-0">
                        <tr><td class="font-weight-bold" width="120">Provinsi</td><td>: <span id="hasil-provinsi">-</span></td></tr>
                        <tr><td class="font-weight-bold">Kota</td><td>: <span id="hasil-kota">-</span></td></tr>
                        <tr><td class="font-weight-bold">Kecamatan</td><td>: <span id="hasil-kecamatan">-</span></td></tr>
                        <tr><td class="font-weight-bold">Kelurahan</td><td>: <span id="hasil-kelurahan">-</span></td></tr>
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
                <small class="text-muted">Log Ajax request akan tampil di console browser (F12 → Console)</small>
                <div id="log-panel" class="mt-3" style="font-size:12px; max-height:300px; overflow-y:auto;">
                    <div class="text-muted"><i>Belum ada request...</i></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script>
    function addLog(message, type) {
        const color = type === 'success' ? 'text-success' : (type === 'error' ? 'text-danger' : 'text-info');
        const time  = new Date().toLocaleTimeString('id-ID');
        $('#log-panel').prepend(`<div class="${color}">[${time}] ${message}</div>`);
    }

    function resetSelect(id, label) {
        $(`#${id}`).html(`<option value="">-- Pilih ${label} --</option>`).prop('disabled', true);
    }

    // ==================== PROVINSI CHANGE ====================
    $('#select-provinsi').on('change', function () {
        const idProvinsi = $(this).val();
        const namaProvinsi = $(this).find('option:selected').text();

        resetSelect('select-kota', 'Kota');
        resetSelect('select-kecamatan', 'Kecamatan');
        resetSelect('select-kelurahan', 'Kelurahan');
        $('#hasil-wilayah').hide();

        if (!idProvinsi) return;

        addLog(`GET /wilayah/kota?id_provinsi=${idProvinsi}`, 'info');

        $.ajax({
            url: '{{ route("wilayah.kota") }}',
            method: 'GET',
            data: { id_provinsi: idProvinsi },
            success: function (response) {
                console.log('Response kota:', response);
                if (response.status === 'success') {
                    addLog(`✓ ${response.data.length} kota ditemukan`, 'success');
                    response.data.forEach(function (kota) {
                        $('#select-kota').append(`<option value="${kota.id}">${kota.nama}</option>`);
                    });
                    $('#select-kota').prop('disabled', false);
                    $('#hasil-provinsi').text(namaProvinsi);
                    $('#hasil-kota, #hasil-kecamatan, #hasil-kelurahan').text('-');
                    $('#hasil-wilayah').show();
                }
            },
            error: function (xhr) {
                addLog('✗ Gagal mengambil data kota', 'error');
                console.log(xhr);
            }
        });
    });

    // ==================== KOTA CHANGE ====================
    $('#select-kota').on('change', function () {
        const idKota = $(this).val();
        const namaKota = $(this).find('option:selected').text();

        resetSelect('select-kecamatan', 'Kecamatan');
        resetSelect('select-kelurahan', 'Kelurahan');

        if (!idKota) return;

        addLog(`GET /wilayah/kecamatan?id_kota=${idKota}`, 'info');

        $.ajax({
            url: '{{ route("wilayah.kecamatan") }}',
            method: 'GET',
            data: { id_kota: idKota },
            success: function (response) {
                console.log('Response kecamatan:', response);
                if (response.status === 'success') {
                    addLog(`✓ ${response.data.length} kecamatan ditemukan`, 'success');
                    response.data.forEach(function (kec) {
                        $('#select-kecamatan').append(`<option value="${kec.id}">${kec.nama}</option>`);
                    });
                    $('#select-kecamatan').prop('disabled', false);
                    $('#hasil-kota').text(namaKota);
                    $('#hasil-kecamatan, #hasil-kelurahan').text('-');
                }
            },
            error: function (xhr) {
                addLog('✗ Gagal mengambil data kecamatan', 'error');
                console.log(xhr);
            }
        });
    });

    // ==================== KECAMATAN CHANGE ====================
    $('#select-kecamatan').on('change', function () {
        const idKecamatan = $(this).val();
        const namaKecamatan = $(this).find('option:selected').text();

        resetSelect('select-kelurahan', 'Kelurahan');

        if (!idKecamatan) return;

        addLog(`GET /wilayah/kelurahan?id_kecamatan=${idKecamatan}`, 'info');

        $.ajax({
            url: '{{ route("wilayah.kelurahan") }}',
            method: 'GET',
            data: { id_kecamatan: idKecamatan },
            success: function (response) {
                console.log('Response kelurahan:', response);
                if (response.status === 'success') {
                    addLog(`✓ ${response.data.length} kelurahan ditemukan`, 'success');
                    response.data.forEach(function (kel) {
                        $('#select-kelurahan').append(`<option value="${kel.id}">${kel.nama}</option>`);
                    });
                    $('#select-kelurahan').prop('disabled', false);
                    $('#hasil-kecamatan').text(namaKecamatan);
                    $('#hasil-kelurahan').text('-');
                }
            },
            error: function (xhr) {
                addLog('✗ Gagal mengambil data kelurahan', 'error');
                console.log(xhr);
            }
        });
    });

    // ==================== KELURAHAN CHANGE ====================
    $('#select-kelurahan').on('change', function () {
        const namaKelurahan = $(this).find('option:selected').text();
        if ($(this).val()) {
            $('#hasil-kelurahan').text(namaKelurahan);
            addLog(`✓ Kelurahan dipilih: ${namaKelurahan}`, 'success');
        }
    });
</script>
@endsection
