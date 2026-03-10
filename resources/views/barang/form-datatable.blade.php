@extends('layouts.admin')

@section('title', 'Form Barang - DataTables')

@section('style_page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    #tabel-barang-dt tbody tr { cursor: pointer; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-package-variant"></i>
        </span> Form Barang (DataTables)
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Form Barang - DataTables</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Barang</h4>

                <form id="form-barang-dt" class="forms-sample">
                    <div class="form-group">
                        <label for="nama_barang_dt">Nama barang :</label>
                        <input type="text" class="form-control" id="nama_barang_dt"
                               placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="form-group">
                        <label for="harga_barang_dt">Harga barang:</label>
                        <input type="number" class="form-control" id="harga_barang_dt"
                               placeholder="Masukkan harga barang" min="0" required>
                    </div>
                </form>
                <div class="d-flex justify-content-end mt-3 mb-4">
                    <button type="button" id="btn-submit-dt" class="btn btn-gradient-success">
                        <span id="btn-submit-dt-text"><i class="mdi mdi-check"></i> submit</span>
                        <span id="btn-submit-dt-spinner" class="d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Memproses...
                        </span>
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabel-barang-dt">
                        <thead>
                            <tr>
                                <th>ID barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Edit/Hapus Barang -->
<div class="modal fade" id="modal-barang-dt" tabindex="-1" role="dialog" aria-labelledby="modalBarangDtLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangDtLabel">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-modal-dt">
                    <div class="form-group">
                        <label for="modal-id-dt">ID barang :</label>
                        <input type="text" class="form-control" id="modal-id-dt" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modal-nama-dt">Nama barang :</label>
                        <input type="text" class="form-control" id="modal-nama-dt" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-harga-dt">Harga barang:</label>
                        <input type="number" class="form-control" id="modal-harga-dt" min="0" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" id="btn-hapus-dt" class="btn btn-gradient-danger">
                    <span id="btn-hapus-dt-text"><i class="mdi mdi-delete"></i> Hapus</span>
                    <span id="btn-hapus-dt-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menghapus...
                    </span>
                </button>
                <button type="button" id="btn-ubah-dt" class="btn btn-gradient-success">
                    <span id="btn-ubah-dt-text"><i class="mdi mdi-pencil"></i> Ubah</span>
                    <span id="btn-ubah-dt-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Mengubah...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    let idCounter  = 1;
    let selectedRow   = null;
    let selectedDtRow = null;

    const dt = $('#tabel-barang-dt').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        },
        columns: [
            { title: 'ID barang' },
            { title: 'Nama' },
            { title: 'Harga' }
        ],
        order: []
    });

    // ---- Submit form (tambah barang) ----
    document.getElementById('btn-submit-dt').addEventListener('click', function () {
        const form = document.getElementById('form-barang-dt');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        const btnText    = document.getElementById('btn-submit-dt-text');
        const btnSpinner = document.getElementById('btn-submit-dt-spinner');
        const btn        = this;

        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
        btn.disabled = true;

        setTimeout(function () {
            const idStr = `BRG-${String(idCounter).padStart(3, '0')}`;
            const nama  = document.getElementById('nama_barang_dt').value;
            const harga = document.getElementById('harga_barang_dt').value;

            const newRow = dt.row.add([
                idStr,
                nama,
                `Rp ${Number(harga).toLocaleString('id-ID')}`
            ]);
            newRow.draw();
            $(newRow.node()).attr({ 'data-id': idStr, 'data-nama': nama, 'data-harga': harga });
            idCounter++;

            document.getElementById('nama_barang_dt').value  = '';
            document.getElementById('harga_barang_dt').value = '';

            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
            btn.disabled = false;
            document.getElementById('nama_barang_dt').focus();
        }, 500);
    });

    // ---- Klik row: buka modal ----
    $('#tabel-barang-dt tbody').on('click', 'tr', function () {
        selectedRow   = this;
        selectedDtRow = dt.row(this);

        document.getElementById('modal-id-dt').value    = $(this).attr('data-id')    || '';
        document.getElementById('modal-nama-dt').value  = $(this).attr('data-nama')  || '';
        document.getElementById('modal-harga-dt').value = $(this).attr('data-harga') || '';
        $('#modal-barang-dt').modal('show');
    });

    // ---- Hapus ----
    document.getElementById('btn-hapus-dt').addEventListener('click', function () {
        const btn        = this;
        const btnText    = document.getElementById('btn-hapus-dt-text');
        const btnSpinner = document.getElementById('btn-hapus-dt-spinner');

        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
        btn.disabled = true;

        setTimeout(function () {
            if (selectedDtRow) {
                selectedDtRow.remove().draw();
                selectedDtRow = null;
                selectedRow   = null;
            }
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
            btn.disabled = false;
            $('#modal-barang-dt').modal('hide');
        }, 500);
    });

    // ---- Ubah ----
    document.getElementById('btn-ubah-dt').addEventListener('click', function () {
        const form = document.getElementById('form-modal-dt');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        const btn        = this;
        const btnText    = document.getElementById('btn-ubah-dt-text');
        const btnSpinner = document.getElementById('btn-ubah-dt-spinner');

        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
        btn.disabled = true;

        setTimeout(function () {
            const idStr = document.getElementById('modal-id-dt').value;
            const nama  = document.getElementById('modal-nama-dt').value;
            const harga = document.getElementById('modal-harga-dt').value;

            if (selectedDtRow) {
                selectedDtRow.data([
                    idStr,
                    nama,
                    `Rp ${Number(harga).toLocaleString('id-ID')}`
                ]).draw();
                $(selectedDtRow.node()).attr({ 'data-id': idStr, 'data-nama': nama, 'data-harga': harga });
            }

            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
            btn.disabled = false;
            $('#modal-barang-dt').modal('hide');
        }, 500);
    });
</script>
@endsection
