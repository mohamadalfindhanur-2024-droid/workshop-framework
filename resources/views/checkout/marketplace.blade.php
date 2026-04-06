@extends('layouts.admin')

@section('title', 'Checkout Marketplace')

@section('style_page')
<style>
    .payment-option {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 12px;
        cursor: pointer;
        transition: all .2s ease;
    }

    .payment-option.active {
        border-color: #6610f2;
        background: #f5f0ff;
    }

    .status-pill {
        font-size: 12px;
        font-weight: 600;
        border-radius: 99px;
        padding: 4px 10px;
        display: inline-block;
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
            <i class="mdi mdi-cart"></i>
        </span> Checkout Marketplace
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout Marketplace</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">1) Pilih Barang</h4>
                <p class="card-description">Masukkan jumlah barang seperti checkout marketplace.</p>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Pilih</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th width="140">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="item-check" value="{{ $item->id_barang }}"
                                               data-kode="{{ $item->id_barang }}"
                                               data-nama="{{ $item->nama }}"
                                               data-harga="{{ $item->harga }}">
                                    </td>
                                    <td>{{ $item->id_barang }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <input type="number" class="form-control qty-input" min="1" value="1" data-id="{{ $item->id_barang }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card w-100">
            <div class="card-body">
                <h4 class="card-title">2) Pembayaran</h4>

                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <div class="payment-option active mb-2" data-method="qris" id="opt-qris">
                        <input type="radio" name="metode_pembayaran" value="qris" checked> QRIS
                    </div>
                    <div class="payment-option" data-method="va" id="opt-va">
                        <input type="radio" name="metode_pembayaran" value="va"> Virtual Account
                    </div>
                </div>

                <div class="mb-3 d-none" id="bank-va-wrapper">
                    <label class="form-label">Pilih Bank VA</label>
                    <select class="form-control" id="bank-va">
                        <option value="">-- Pilih Bank --</option>
                        <option value="BCA">BCA</option>
                        <option value="BNI">BNI</option>
                        <option value="BRI">BRI</option>
                        <option value="MANDIRI">MANDIRI</option>
                    </select>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong id="summary-subtotal">Rp 0</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Biaya Layanan</span>
                    <strong id="summary-fee">Rp 2.500</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Total Bayar</span>
                    <strong id="summary-total">Rp 0</strong>
                </div>

                <button id="btn-checkout" class="btn btn-gradient-primary w-100">
                    <span id="btn-checkout-text"><i class="mdi mdi-credit-card-outline"></i> Buat Checkout</span>
                    <span id="btn-checkout-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row" id="payment-result-wrapper" style="display:none;">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">3) Instruksi Pembayaran</h4>

                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-1"><strong>Order:</strong> <span id="res-kode"></span></p>
                        <p class="mb-1"><strong>Mode:</strong> <span id="res-mode" class="badge bg-secondary">-</span></p>
                        <p class="mb-1"><strong>Metode:</strong> <span id="res-method"></span></p>
                        <p class="mb-1"><strong>Total:</strong> <span id="res-total"></span></p>
                        <p class="mb-1"><strong>Kode Bayar:</strong> <code id="res-code"></code></p>
                        <p class="mb-1" id="res-va-row" style="display:none;"><strong>Bank VA:</strong> <span id="res-bank"></span></p>
                        <p class="mb-2"><strong>Batas Bayar:</strong> <span id="res-expired"></span> (<span id="res-countdown">--:--</span>)</p>
                        <p class="mb-0">Status: <span id="res-status" class="status-pill status-pending">PENDING</span></p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img id="qris-image" alt="QRIS" class="img-fluid border rounded p-2" style="max-width:220px;">
                        <div id="qris-fallback" class="small text-muted mt-2" style="display:none;">
                            Gagal memuat gambar QR dari internet. Silakan gunakan kode pembayaran di sebelah kiri.
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button id="btn-simulate-paid" class="btn btn-gradient-success me-2">
                        <i class="mdi mdi-check-circle"></i> Simulasi Bayar Berhasil
                    </button>
                    <button id="btn-refresh-status" class="btn btn-gradient-info">
                        <i class="mdi mdi-refresh"></i> Refresh Status
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script>
(function () {
    const fee = 2500;
    let currentSubtotal = 0;
    let currentTotal = 0;
    let currentTransactionId = null;
    let currentExpiry = null;
    let pollTimer = null;
    let countdownTimer = null;

    function toRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
    }

    function getSelectedItems() {
        const rows = [];
        document.querySelectorAll('.item-check:checked').forEach((chk) => {
            const id = chk.value;
            const qtyInput = document.querySelector('.qty-input[data-id="' + id + '"]');
            const qty = parseInt(qtyInput ? qtyInput.value : '1', 10) || 1;
            const harga = parseFloat(chk.dataset.harga || '0');
            rows.push({
                id_barang: chk.dataset.kode,
                nama_barang: chk.dataset.nama,
                harga: harga,
                jumlah: qty,
                subtotal: harga * qty,
            });
        });
        return rows;
    }

    function refreshSummary() {
        const items = getSelectedItems();
        currentSubtotal = items.reduce((acc, item) => acc + item.subtotal, 0);
        currentTotal = currentSubtotal > 0 ? currentSubtotal + fee : 0;
        document.getElementById('summary-subtotal').textContent = toRupiah(currentSubtotal);
        document.getElementById('summary-total').textContent = toRupiah(currentTotal);
    }

    function selectedMethod() {
        return document.querySelector('input[name="metode_pembayaran"]:checked').value;
    }

    function renderMethodState() {
        const method = selectedMethod();
        document.getElementById('opt-qris').classList.toggle('active', method === 'qris');
        document.getElementById('opt-va').classList.toggle('active', method === 'va');
        document.getElementById('bank-va-wrapper').classList.toggle('d-none', method !== 'va');
    }

    function setCheckoutLoading(isLoading) {
        document.getElementById('btn-checkout').disabled = isLoading;
        document.getElementById('btn-checkout-text').classList.toggle('d-none', isLoading);
        document.getElementById('btn-checkout-spinner').classList.toggle('d-none', !isLoading);
    }

    function statusClass(status) {
        if (status === 'paid') return 'status-pill status-paid';
        if (status === 'expired') return 'status-pill status-expired';
        if (status === 'failed') return 'status-pill status-failed';
        return 'status-pill status-pending';
    }

    function statusLabel(status) {
        return String(status || 'pending').toUpperCase();
    }

    function updateStatusUI(status) {
        const el = document.getElementById('res-status');
        el.className = statusClass(status);
        el.textContent = statusLabel(status);
        const paid = status === 'paid';
        const expired = status === 'expired';
        const failed = status === 'failed';
        document.getElementById('btn-simulate-paid').disabled = paid || expired || failed;
    }

    function startPolling() {
        if (pollTimer) clearInterval(pollTimer);
        pollTimer = setInterval(fetchStatus, 5000);
    }

    function startCountdown() {
        if (countdownTimer) clearInterval(countdownTimer);
        countdownTimer = setInterval(() => {
            if (!currentExpiry) return;
            const diff = Math.max(0, new Date(currentExpiry).getTime() - Date.now());
            const mm = String(Math.floor(diff / 60000)).padStart(2, '0');
            const ss = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
            document.getElementById('res-countdown').textContent = mm + ':' + ss;

            if (diff <= 0) {
                clearInterval(countdownTimer);
                fetchStatus();
            }
        }, 1000);
    }

    function fetchStatus() {
        if (!currentTransactionId) return;
        fetch('{{ url('/checkout/status') }}/' + currentTransactionId)
            .then((r) => r.json())
            .then((res) => {
                if (res && res.data) {
                    updateStatusUI(res.data.status_order);
                    if (res.data.status_order !== 'pending') {
                        if (pollTimer) clearInterval(pollTimer);
                        if (countdownTimer) clearInterval(countdownTimer);
                    }
                }
            })
            .catch(() => {});
    }

    document.querySelectorAll('.item-check, .qty-input').forEach((el) => {
        el.addEventListener('change', refreshSummary);
        el.addEventListener('keyup', refreshSummary);
    });

    document.querySelectorAll('input[name="metode_pembayaran"]').forEach((radio) => {
        radio.addEventListener('change', renderMethodState);
    });

    document.getElementById('opt-qris').addEventListener('click', () => {
        document.querySelector('input[name="metode_pembayaran"][value="qris"]').checked = true;
        renderMethodState();
    });

    document.getElementById('opt-va').addEventListener('click', () => {
        document.querySelector('input[name="metode_pembayaran"][value="va"]').checked = true;
        renderMethodState();
    });

    document.getElementById('btn-checkout').addEventListener('click', function () {
        const items = getSelectedItems();
        if (items.length === 0) {
            alert('Pilih minimal 1 barang dulu.');
            return;
        }

        const method = selectedMethod();
        const bankVa = document.getElementById('bank-va').value;
        if (method === 'va' && !bankVa) {
            alert('Pilih bank Virtual Account.');
            return;
        }

        setCheckoutLoading(true);

        fetch('{{ route('checkout.process') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                items: items,
                metode_pembayaran: method,
                bank_va: bankVa || null,
                total: currentTotal,
            }),
        })
        .then(async (r) => {
            const body = await r.json();
            if (!r.ok) {
                throw new Error(body.message || 'Checkout gagal');
            }
            return body;
        })
        .then((res) => {
            const data = res.data;
            currentTransactionId = data.id_transaksi;
            currentExpiry = data.expires_at;

            document.getElementById('payment-result-wrapper').style.display = 'block';
            document.getElementById('res-kode').textContent = data.kode_transaksi;
            document.getElementById('res-mode').textContent = data.is_simulator ? 'SIMULATOR' : 'MIDTRANS';
            document.getElementById('res-mode').className = data.is_simulator ? 'badge bg-warning' : 'badge bg-success';
            document.getElementById('res-method').textContent = data.metode_pembayaran === 'qris' ? 'QRIS' : 'Virtual Account';
            document.getElementById('res-total').textContent = toRupiah(data.total);
            document.getElementById('res-code').textContent = data.payment_code;
            document.getElementById('res-expired').textContent = new Date(data.expires_at).toLocaleString('id-ID');
            document.getElementById('btn-simulate-paid').style.display = data.is_simulator ? 'inline-block' : 'none';

            const isQris = data.metode_pembayaran === 'qris';
            const qrisImage = document.getElementById('qris-image');
            const vaRow = document.getElementById('res-va-row');
            if (isQris) {
                vaRow.style.display = 'none';
                qrisImage.style.display = 'inline-block';
                document.getElementById('qris-fallback').style.display = 'none';
                qrisImage.onerror = function () {
                    document.getElementById('qris-fallback').style.display = 'block';
                };
                if (data.qr_url) {
                    qrisImage.src = data.qr_url;
                } else {
                    qrisImage.src = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' + encodeURIComponent(data.payment_payload || data.payment_code);
                }
            } else {
                vaRow.style.display = 'block';
                document.getElementById('res-bank').textContent = data.bank_va || '-';
                qrisImage.style.display = 'none';
                qrisImage.removeAttribute('src');
                document.getElementById('qris-fallback').style.display = 'none';
            }

            updateStatusUI(data.status_order);
            startPolling();
            startCountdown();
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        })
        .catch((err) => {
            alert(err.message || 'Checkout gagal.');
        })
        .finally(() => {
            setCheckoutLoading(false);
        });
    });

    document.getElementById('btn-simulate-paid').addEventListener('click', function () {
        if (!currentTransactionId) return;
        fetch('{{ url('/checkout/simulate-paid') }}/' + currentTransactionId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(async (r) => {
            const body = await r.json();
            if (!r.ok) {
                throw new Error(body.message || 'Simulasi gagal');
            }
            return body;
        })
        .then(() => fetchStatus())
        .catch((err) => alert(err.message));
    });

    document.getElementById('btn-refresh-status').addEventListener('click', fetchStatus);

    refreshSummary();
    renderMethodState();
})();
</script>
@endsection
