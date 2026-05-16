@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h4 class="mb-2">QR Code Toko</h4>
                <p class="text-muted mb-3">{{ $toko->nama }}</p>

                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($toko->barcode) }}" alt="QR Code {{ $toko->barcode }}" class="img-fluid rounded border p-2 bg-white">

                <div class="mt-3">
                    <strong>{{ $toko->barcode }}</strong>
                </div>

                <div class="mt-4">
                    <a href="/kunjungan-toko" class="btn btn-light">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
