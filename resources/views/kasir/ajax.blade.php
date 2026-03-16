@extends('layouts.admin')

@section('title', 'Kasir - Ajax jQuery')

@section('style_page')
<style>
    #tabel-kasir tbody tr td { vertical-align: middle; }
    .input-jumlah { width: 70px; text-align: center; }
    #row-total td { font-size: 1.1rem; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-cash-register"></i>
        </span> Point of Sales (Ajax jQuery)
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Kasir Ajax</li>
        </ol>
    </nav>
</div>

<div class="row">
    {{-- Form Input Barang --}}
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><i class="mdi mdi-barcode-scan"></i> Input Barang <span class="badge badge-gradient-primary">Ajax jQuery</span></h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="font-weight-bold">Kode Barang :</label>
                    <input type="text" class="form-control" id="input-kode"
                           placeholder="Ketik kode lalu tekan Enter">
                    <small class="text-muted">Tekan Enter untuk mencari barang</small>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Nama Barang :</label>
                    <input type="text" class="form-control" id="input-nama" readonly placeholder="-">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Harga Barang :</label>
                    <input type="text" class="form-control" id="input-harga" readonly placeholder="-">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Jumlah :</label>
                    <input type="number" class="form-control" id="input-jumlah"
                           value="1" min="1" disabled>
                </div>
                <button type="button" id="btn-tambahkan" class="btn btn-gradient-success btn-block" disabled>
                    <span id="btn-tambah-text"><i class="mdi mdi-plus-circle"></i> Tambahkan</span>
                    <span id="btn-tambah-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menambahkan...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="mdi mdi-cart"></i> Daftar Belanja</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="tabel-kasir">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th width="90">Jumlah</th>
                                <th>Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-kasir">
                            <tr id="row-empty"><td colspan="6" class="text-center text-muted py-4">Belum ada barang ditambahkan</td></tr>
                        </tbody>
                        <tfoot>
                            <tr id="row-total" style="background:#f8f9fa;">
                                <td colspan="4" class="text-right font-weight-bold">TOTAL</td>
                                <td colspan="2" class="font-weight-bold text-success" id="total-display">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="button" id="btn-bayar" class="btn btn-gradient-danger btn-lg" disabled>
                    <span id="btn-bayar-text"><i class="mdi mdi-cash"></i> Bayar</span>
                    <span id="btn-bayar-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('javascript_page')
<script>
    let barangDitemukan = null;
    let totalBelanja    = 0;

    // ==================== CARI BARANG (Enter) ====================
    $('#input-kode').on('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        const kode = $(this).val().trim();
        if (!kode) return;

        resetFormBarang();

        $.ajax({
            url: '{{ route("kasir.cari-barang") }}',
            method: 'GET',
            data: { kode: kode },
            success: function (response) {
                console.log('Response cari barang:', response);
                if (response.status === 'success') {
                    barangDitemukan = response.data;
                    $('#input-nama').val(barangDitemukan.nama);
                    $('#input-harga').val('Rp ' + Number(barangDitemukan.harga).toLocaleString('id-ID'));
                    $('#input-jumlah').val(1).prop('disabled', false);
                    $('#btn-tambahkan').prop('disabled', false);
                    $('#input-jumlah').focus();
                } else {
                    Swal.fire({ icon: 'error', title: 'Tidak Ditemukan', text: response.message, timer: 2000, showConfirmButton: false });
                    $('#input-kode').focus();
                }
            },
            error: function (xhr) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server' });
                console.log(xhr);
            }
        });
    });

    // ==================== TAMBAHKAN KE TABEL ====================
    $('#btn-tambahkan').on('click', function () {
        if (!barangDitemukan) return;
        const jumlah = parseInt($('#input-jumlah').val());
        if (jumlah < 1) return;

        const btnText    = $('#btn-tambah-text');
        const btnSpinner = $('#btn-tambah-spinner');
        const btn        = $(this);
        btnText.addClass('d-none');
        btnSpinner.removeClass('d-none');
        btn.prop('disabled', true);

        setTimeout(function () {
            addRow(barangDitemukan.id_barang, barangDitemukan.nama, barangDitemukan.harga, jumlah);
            resetFormBarang();
            resetInputBarang();
            btnText.removeClass('d-none');
            btnSpinner.addClass('d-none');
            $('#btn-tambahkan').prop('disabled', true);
            $('#input-kode').focus();
        }, 400);
    });

    function addRow(kode, nama, harga, jumlah) {
        // Cek apakah kode sudah ada di tabel
        const existingRow = $(`#tbody-kasir tr[data-kode="${kode}"]`);
        if (existingRow.length) {
            const oldJumlah = parseInt(existingRow.find('.input-jumlah').val());
            const newJumlah = oldJumlah + jumlah;
            existingRow.find('.input-jumlah').val(newJumlah);
            const subtotal = newJumlah * harga;
            existingRow.find('.col-subtotal').text('Rp ' + Number(subtotal).toLocaleString('id-ID'));
            existingRow.attr('data-jumlah', newJumlah).attr('data-subtotal', subtotal);
        } else {
            $('#row-empty').hide();
            const subtotal = jumlah * harga;
            const tr = `<tr data-kode="${kode}" data-harga="${harga}" data-jumlah="${jumlah}" data-subtotal="${subtotal}">
                <td>${kode}</td>
                <td>${nama}</td>
                <td>Rp ${Number(harga).toLocaleString('id-ID')}</td>
                <td><input type="number" class="form-control input-jumlah" value="${jumlah}" min="1"></td>
                <td class="col-subtotal">Rp ${Number(subtotal).toLocaleString('id-ID')}</td>
                <td><button type="button" class="btn btn-sm btn-gradient-danger btn-hapus-row"><i class="mdi mdi-delete"></i></button></td>
            </tr>`;
            $('#tbody-kasir').append(tr);
        }
        hitungTotal();
    }

    // ==================== UPDATE JUMLAH DI TABEL ====================
    $('#tbody-kasir').on('input', '.input-jumlah', function () {
        const tr      = $(this).closest('tr');
        const jumlah  = parseInt($(this).val()) || 0;
        const harga   = parseFloat(tr.attr('data-harga'));
        const subtotal = jumlah * harga;
        tr.attr('data-jumlah', jumlah).attr('data-subtotal', subtotal);
        tr.find('.col-subtotal').text('Rp ' + Number(subtotal).toLocaleString('id-ID'));
        hitungTotal();
    });

    // ==================== HAPUS BARIS ====================
    $('#tbody-kasir').on('click', '.btn-hapus-row', function () {
        $(this).closest('tr').remove();
        hitungTotal();
        if ($('#tbody-kasir tr:visible').length === 0) {
            $('#row-empty').show();
        }
    });

    // ==================== HITUNG TOTAL ====================
    function hitungTotal() {
        totalBelanja = 0;
        $('#tbody-kasir tr[data-kode]').each(function () {
            totalBelanja += parseFloat($(this).attr('data-subtotal')) || 0;
        });
        $('#total-display').text('Rp ' + Number(totalBelanja).toLocaleString('id-ID'));
        $('#btn-bayar').prop('disabled', totalBelanja <= 0);
    }

    // ==================== BAYAR ====================
    $('#btn-bayar').on('click', function () {
        const items = [];
        $('#tbody-kasir tr[data-kode]').each(function () {
            items.push({
                id_barang:   $(this).attr('data-kode'),
                nama_barang: $(this).find('td:eq(1)').text(),
                harga:       parseFloat($(this).attr('data-harga')),
                jumlah:      parseInt($(this).find('.input-jumlah').val()),
                subtotal:    parseFloat($(this).attr('data-subtotal')),
            });
        });

        if (items.length === 0) return;

        const btnText    = $('#btn-bayar-text');
        const btnSpinner = $('#btn-bayar-spinner');
        const btn        = $(this);
        btnText.addClass('d-none');
        btnSpinner.removeClass('d-none');
        btn.prop('disabled', true);

        $.ajax({
            url: '{{ route("kasir.bayar") }}',
            method: 'POST',
            data: JSON.stringify({ items: items, total: totalBelanja }),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                console.log('Response bayar:', response);
                btnText.removeClass('d-none');
                btnSpinner.addClass('d-none');
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Transaksi Berhasil!',
                        text: response.message,
                        timer: 2500,
                        showConfirmButton: false,
                    }).then(function () {
                        resetSemua();
                    });
                } else {
                    btn.prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                }
            },
            error: function (xhr) {
                btnText.removeClass('d-none');
                btnSpinner.addClass('d-none');
                btn.prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server' });
                console.log(xhr);
            }
        });
    });

    function resetFormBarang() {
        barangDitemukan = null;
        $('#input-nama, #input-harga').val('');
        $('#input-jumlah').val(1).prop('disabled', true);
        $('#btn-tambahkan').prop('disabled', true);
    }

    function resetInputBarang() {
        $('#input-kode').val('');
    }

    function resetSemua() {
        $('#tbody-kasir tr[data-kode]').remove();
        $('#row-empty').show();
        totalBelanja = 0;
        $('#total-display').text('Rp 0');
        $('#btn-bayar').prop('disabled', true);
        resetFormBarang();
        resetInputBarang();
        $('#input-kode').focus();
    }
</script>
@endsection
