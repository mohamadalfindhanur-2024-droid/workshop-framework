@extends('layouts.admin')

@section('title', 'Select Kota')

@section('style_page')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        color: #495057;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container { width: 100% !important; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker"></i>
        </span> Select Kota
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Select Kota</li>
        </ol>
    </nav>
</div>

<div class="row">

    {{-- ===== CARD 1: SELECT BIASA ===== --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Select</h4>
            </div>
            <div class="card-body">

                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label">Kota:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="input-kota-1" placeholder="Masukkan nama kota">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="button" id="btn-tambah-1" class="btn btn-gradient-success">
                            Tambahkan
                        </button>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label">Select Kota:</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="select-kota-1" size="1">
                        </select>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label">Kota Terpilih:</label>
                    <div class="col-sm-9">
                        <span id="kota-terpilih-1" class="font-weight-bold text-primary">-</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== CARD 2: SELECT2 ===== --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">select 2</h4>
            </div>
            <div class="card-body">

                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label">Kota:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="input-kota-2" placeholder="Masukkan nama kota">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="button" id="btn-tambah-2" class="btn btn-gradient-success">
                            Tambahkan
                        </button>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label">Select Kota:</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="select-kota-2">
                        </select>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label">Kota Terpilih:</label>
                    <div class="col-sm-9">
                        <span id="kota-terpilih-2" class="font-weight-bold text-primary">-</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('javascript_page')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // ===== CARD 1: SELECT BIASA =====
    document.getElementById('btn-tambah-1').addEventListener('click', function () {
        const input = document.getElementById('input-kota-1');
        const nama  = input.value.trim();
        if (!nama) {
            input.focus();
            return;
        }
        const select = document.getElementById('select-kota-1');
        const option = document.createElement('option');
        option.value       = nama;
        option.textContent = nama;
        select.appendChild(option);

        input.value = '';
        input.focus();
    });

    document.getElementById('select-kota-1').addEventListener('change', function () {
        document.getElementById('kota-terpilih-1').textContent = this.value || '-';
    });

    // ===== CARD 2: SELECT2 =====
    $('#select-kota-2').select2({
        placeholder: 'Pilih kota...',
        allowClear: true,
        width: '100%'
    });

    document.getElementById('btn-tambah-2').addEventListener('click', function () {
        const input = document.getElementById('input-kota-2');
        const nama  = input.value.trim();
        if (!nama) {
            input.focus();
            return;
        }
        const option = new Option(nama, nama, false, true);
        $('#select-kota-2').append(option).trigger('change');

        input.value = '';
        input.focus();
    });

    $('#select-kota-2').on('change', function () {
        const val = $(this).val();
        document.getElementById('kota-terpilih-2').textContent = val || '-';
    });
</script>
@endsection
