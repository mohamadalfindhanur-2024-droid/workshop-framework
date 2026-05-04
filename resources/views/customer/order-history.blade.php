@extends('layouts.admin')

@section('title', 'Riwayat Pesanan & QR Code')

@section('style_page')
<style>
    .order-card {
        border: 1px solid #ececf4;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 12px 35px rgba(93, 63, 211, 0.12);
        border-color: #d7dde8;
    }

    .qr-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
        border: 2px dashed #ececf4;
        border-radius: 8px;
        background: #fafbfc;
    }

    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-expired {
        background: #fee2e2;
        color: #991b1b;
    }

    .items-list {
        max-height: 200px;
        overflow-y: auto;
    }

    .items-list .item-row {
        padding: 8px 0;
        border-bottom: 1px solid #ececf4;
        font-size: 0.875rem;
    }

    .items-list .item-row:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-history"></i>
        </span> Riwayat Pesanan & QR Code
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Riwayat Pesanan</li>
        </ol>
    </nav>
</div>

<div class="row g-3">
    @forelse($transaksi as $order)
        <div class="col-lg-6">
            <div class="card order-card h-100">
                <div class="card-body p-4">
                    <!-- Header -->
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <h5 class="mb-1">{{ $order->kode_transaksi ?: ('Order #' . $order->id) }}</h5>
                            <p class="text-muted small mb-0">{{ optional($order->created_at)->format('d M Y H:i') }}</p>
                        </div>
                        <span class="badge status-{{ $order->status_order ?: 'pending' }}">
                            {{ strtoupper($order->status_order ?: 'PENDING') }}
                        </span>
                    </div>

                    <div class="row g-3">
                        <!-- QR Code Section -->
                        <div class="col-auto">
                            <div class="qr-badge">
                                <img id="qr-{{ $order->id }}" alt="QR Code" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                        </div>

                        <!-- Detail Section -->
                        <div class="col">
                            <div class="mb-3">
                                <div class="text-muted small mb-1">ID Pesanan</div>
                                <strong>{{ $order->id }}</strong>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small mb-1">Total Pembayaran</div>
                                <strong class="text-success">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small mb-1">Metode Pembayaran</div>
                                @if($order->metode_pembayaran === 'qris')
                                    <small><i class="mdi mdi-qrcode me-1"></i>QRIS</small>
                                @elseif($order->metode_pembayaran === 'va')
                                    <small><i class="mdi mdi-bank me-1"></i>Virtual Account ({{ $order->bank_va }})</small>
                                @else
                                    <small>-</small>
                                @endif
                            </div>

                            <div>
                                <div class="text-muted small mb-1">Item Pesanan</div>
                                <div class="items-list">
                                    @forelse($order->detail as $item)
                                        <div class="item-row">
                                            <div class="d-flex justify-content-between">
                                                <span>{{ $item->nama_barang }}</span>
                                                <span class="text-muted">x{{ $item->jumlah }}</span>
                                            </div>
                                            <div class="small text-muted">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                        </div>
                                    @empty
                                        <div class="text-muted small">Tidak ada item</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-3 pt-3 border-top d-flex gap-2">
                        <a href="{{ route('struk.cetak', $order->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="mdi mdi-printer me-1"></i> Cetak Struk
                        </a>
                        <button class="btn btn-sm btn-outline-info" onclick="copyQrId({{ $order->id }})">
                            <i class="mdi mdi-content-copy me-1"></i> Copy QR ID
                        </button>
                        @if($order->status_order !== 'paid')
                            <a href="{{ route('checkout.marketplace') }}" class="btn btn-sm btn-outline-warning">
                                <i class="mdi mdi-refresh me-1"></i> Cek Status
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const qrImage = document.getElementById('qr-{{ $order->id }}');
                qrImage.src = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $order->id }}';
            });
        </script>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-3"><i class="mdi mdi-inbox-multiple-outline" style="font-size: 3rem; color: #ccc;"></i></div>
                    <h5 class="mb-2">Belum Ada Riwayat Pesanan</h5>
                    <p class="text-muted mb-3">Mulai berbelanja untuk melihat riwayat pesanan dan QR code di sini.</p>
                    <a href="{{ route('checkout.marketplace') }}" class="btn btn-gradient-primary">
                        <i class="mdi mdi-shopping me-1"></i> Belanja Sekarang
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($transaksi->hasPages())
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-center">
            {{ $transaksi->links() }}
        </div>
    </div>
@endif
@endsection

@section('javascript_page')
<script>
    function copyQrId(orderId) {
        const text = String(orderId);
        navigator.clipboard.writeText(text).then(() => {
            alert('QR ID ' + text + ' berhasil disalin ke clipboard!');
        });
    }
</script>
@endsection
