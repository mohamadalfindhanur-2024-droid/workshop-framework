@extends('layouts.admin')

@section('title', 'Kasir - Axios')

@section('style_page')
<style>
    #tabel-kasir-ax tbody tr td { vertical-align: middle; }
    .input-jumlah { width: 70px; text-align: center; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-cash-register"></i>
        </span> Point of Sales (Axios)
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Kasir Axios</li>
        </ol>
    </nav>
</div>

<div class="row">
    {{-- Form Input Barang --}}
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><i class="mdi mdi-barcode-scan"></i> Input Barang <span class="badge badge-gradient-success">Axios</span></h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="font-weight-bold">Kode Barang :</label>
                    <input type="text" class="form-control" id="ax-kode"
                           placeholder="Ketik kode lalu tekan Enter">
                    <small class="text-muted">Tekan Enter untuk mencari barang</small>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Nama Barang :</label>
                    <input type="text" class="form-control" id="ax-nama" readonly placeholder="-">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Harga Barang :</label>
                    <input type="text" class="form-control" id="ax-harga" readonly placeholder="-">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Jumlah :</label>
                    <input type="number" class="form-control" id="ax-jumlah"
                           value="1" min="1" disabled>
                </div>
                <button type="button" id="ax-btn-tambahkan" class="btn btn-gradient-success btn-block" disabled>
                    <span id="ax-btn-tambah-text"><i class="mdi mdi-plus-circle"></i> Tambahkan</span>
                    <span id="ax-btn-tambah-spinner" class="d-none">
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
            <div class="card-header">
                <h4 class="card-title mb-0"><i class="mdi mdi-cart"></i> Daftar Belanja</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="tabel-kasir-ax">
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
                        <tbody id="ax-tbody">
                            <tr id="ax-row-empty"><td colspan="6" class="text-center text-muted py-4">Belum ada barang ditambahkan</td></tr>
                        </tbody>
                        <tfoot>
                            <tr style="background:#f8f9fa;">
                                <td colspan="4" class="text-right font-weight-bold">TOTAL</td>
                                <td colspan="2" class="font-weight-bold text-success" id="ax-total-display">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="button" id="ax-btn-bayar" class="btn btn-gradient-danger btn-lg" disabled>
                    <span id="ax-btn-bayar-text"><i class="mdi mdi-cash"></i> Bayar</span>
                    <span id="ax-btn-bayar-spinner" class="d-none">
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
{{-- Axios CDN --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Set CSRF token default header
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let axBarangDitemukan = null;
    let axTotal           = 0;

    // ==================== CARI BARANG (Enter) ====================
    document.getElementById('ax-kode').addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        const kode = this.value.trim();
        if (!kode) return;

        axResetFormBarang();

        axios.get('{{ route("kasir.cari-barang") }}', { params: { kode: kode } })
            .then(function (response) {
                console.log('Response cari barang:', response.data);
                const res = response.data;
                if (res.status === 'success') {
                    axBarangDitemukan = res.data;
                    document.getElementById('ax-nama').value  = res.data.nama;
                    document.getElementById('ax-harga').value = 'Rp ' + Number(res.data.harga).toLocaleString('id-ID');
                    const inputJumlah = document.getElementById('ax-jumlah');
                    inputJumlah.value    = 1;
                    inputJumlah.disabled = false;
                    document.getElementById('ax-btn-tambahkan').disabled = false;
                    inputJumlah.focus();
                } else {
                    Swal.fire({ icon: 'error', title: 'Tidak Ditemukan', text: res.message, timer: 2000, showConfirmButton: false });
                    document.getElementById('ax-kode').focus();
                }
            })
            .catch(function (error) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server' });
                console.log(error);
            });
    });

    // ==================== TAMBAHKAN KE TABEL ====================
    document.getElementById('ax-btn-tambahkan').addEventListener('click', function () {
        if (!axBarangDitemukan) return;
        const jumlah = parseInt(document.getElementById('ax-jumlah').value);
        if (jumlah < 1) return;

        const btnText    = document.getElementById('ax-btn-tambah-text');
        const btnSpinner = document.getElementById('ax-btn-tambah-spinner');
        const btn        = this;
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
        btn.disabled = true;

        setTimeout(function () {
            axAddRow(axBarangDitemukan.id_barang, axBarangDitemukan.nama, axBarangDitemukan.harga, jumlah);
            axResetFormBarang();
            document.getElementById('ax-kode').value = '';
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
            document.getElementById('ax-btn-tambahkan').disabled = true;
            document.getElementById('ax-kode').focus();
        }, 400);
    });

    function axAddRow(kode, nama, harga, jumlah) {
        const existingRow = document.querySelector(`#ax-tbody tr[data-kode="${kode}"]`);
        if (existingRow) {
            const inputJumlah = existingRow.querySelector('.input-jumlah');
            const newJumlah   = parseInt(inputJumlah.value) + jumlah;
            inputJumlah.value = newJumlah;
            const subtotal    = newJumlah * harga;
            existingRow.setAttribute('data-jumlah', newJumlah);
            existingRow.setAttribute('data-subtotal', subtotal);
            existingRow.querySelector('.col-subtotal').textContent = 'Rp ' + Number(subtotal).toLocaleString('id-ID');
        } else {
            document.getElementById('ax-row-empty').style.display = 'none';
            const subtotal = jumlah * harga;
            const tr = document.createElement('tr');
            tr.setAttribute('data-kode', kode);
            tr.setAttribute('data-harga', harga);
            tr.setAttribute('data-jumlah', jumlah);
            tr.setAttribute('data-subtotal', subtotal);
            tr.innerHTML = `
                <td>${kode}</td>
                <td>${nama}</td>
                <td>Rp ${Number(harga).toLocaleString('id-ID')}</td>
                <td><input type="number" class="form-control input-jumlah" value="${jumlah}" min="1"></td>
                <td class="col-subtotal">Rp ${Number(subtotal).toLocaleString('id-ID')}</td>
                <td><button type="button" class="btn btn-sm btn-gradient-danger btn-hapus"><i class="mdi mdi-delete"></i></button></td>
            `;
            document.getElementById('ax-tbody').appendChild(tr);
        }
        axHitungTotal();
    }

    // ==================== UPDATE JUMLAH DI TABEL ====================
    document.getElementById('ax-tbody').addEventListener('input', function (e) {
        if (!e.target.classList.contains('input-jumlah')) return;
        const tr       = e.target.closest('tr');
        const jumlah   = parseInt(e.target.value) || 0;
        const harga    = parseFloat(tr.getAttribute('data-harga'));
        const subtotal = jumlah * harga;
        tr.setAttribute('data-jumlah', jumlah);
        tr.setAttribute('data-subtotal', subtotal);
        tr.querySelector('.col-subtotal').textContent = 'Rp ' + Number(subtotal).toLocaleString('id-ID');
        axHitungTotal();
    });

    // ==================== HAPUS BARIS ====================
    document.getElementById('ax-tbody').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus');
        if (!btn) return;
        btn.closest('tr').remove();
        axHitungTotal();
        if (document.querySelectorAll('#ax-tbody tr[data-kode]').length === 0) {
            document.getElementById('ax-row-empty').style.display = '';
        }
    });

    // ==================== HITUNG TOTAL ====================
    function axHitungTotal() {
        axTotal = 0;
        document.querySelectorAll('#ax-tbody tr[data-kode]').forEach(function (tr) {
            axTotal += parseFloat(tr.getAttribute('data-subtotal')) || 0;
        });
        document.getElementById('ax-total-display').textContent = 'Rp ' + Number(axTotal).toLocaleString('id-ID');
        document.getElementById('ax-btn-bayar').disabled = axTotal <= 0;
    }

    // ==================== BAYAR ====================
    document.getElementById('ax-btn-bayar').addEventListener('click', function () {
        const items = [];
        document.querySelectorAll('#ax-tbody tr[data-kode]').forEach(function (tr) {
            items.push({
                id_barang:   tr.getAttribute('data-kode'),
                nama_barang: tr.querySelectorAll('td')[1].textContent,
                harga:       parseFloat(tr.getAttribute('data-harga')),
                jumlah:      parseInt(tr.querySelector('.input-jumlah').value),
                subtotal:    parseFloat(tr.getAttribute('data-subtotal')),
            });
        });

        if (items.length === 0) return;

        const btnText    = document.getElementById('ax-btn-bayar-text');
        const btnSpinner = document.getElementById('ax-btn-bayar-spinner');
        const btn        = this;
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
        btn.disabled = true;

        axios.post('{{ route("kasir.bayar") }}', { items: items, total: axTotal })
            .then(function (response) {
                console.log('Response bayar:', response.data);
                btnText.classList.remove('d-none');
                btnSpinner.classList.add('d-none');
                const res = response.data;
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Transaksi Berhasil!',
                        text: res.message,
                        timer: 2500,
                        showConfirmButton: false,
                    }).then(function () {
                        axResetSemua();
                    });
                } else {
                    btn.disabled = false;
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                }
            })
            .catch(function (error) {
                btnText.classList.remove('d-none');
                btnSpinner.classList.add('d-none');
                btn.disabled = false;
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server' });
                console.log(error);
            });
    });

    function axResetFormBarang() {
        axBarangDitemukan = null;
        document.getElementById('ax-nama').value  = '';
        document.getElementById('ax-harga').value = '';
        document.getElementById('ax-jumlah').value    = 1;
        document.getElementById('ax-jumlah').disabled = true;
        document.getElementById('ax-btn-tambahkan').disabled = true;
    }

    function axResetSemua() {
        document.querySelectorAll('#ax-tbody tr[data-kode]').forEach(tr => tr.remove());
        document.getElementById('ax-row-empty').style.display = '';
        axTotal = 0;
        document.getElementById('ax-total-display').textContent = 'Rp 0';
        document.getElementById('ax-btn-bayar').disabled = true;
        axResetFormBarang();
        document.getElementById('ax-kode').value = '';
        document.getElementById('ax-kode').focus();
    }
</script>
@endsection
