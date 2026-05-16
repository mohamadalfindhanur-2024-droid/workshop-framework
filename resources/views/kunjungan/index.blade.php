@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1">Kunjungan Toko</h4>
                <p class="text-muted mb-0">Data lokasi toko untuk validasi kunjungan sales.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="/tambah-toko" class="btn btn-primary btn-sm">+ Tambah Toko</a>
                <a href="/scan-kunjungan" class="btn btn-outline-primary btn-sm">Scan Kunjungan</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 70px;">No</th>
                        <th>Barcode</th>
                        <th>Nama Toko</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th class="text-center">Accuracy</th>
                        <th class="text-center" style="width: 130px;">QR Code</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokos as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td><strong>{{ $item->barcode }}</strong></td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->latitude }}</td>
                            <td>{{ $item->longitude }}</td>
                            <td class="text-center">{{ $item->accuracy ? $item->accuracy . ' m' : '-' }}</td>
                            <td class="text-center">
                                <a href="/qrcode/{{ $item->id }}" class="btn btn-info btn-sm">
                                    <i class="mdi mdi-qrcode"></i> Lihat QR
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Data toko belum tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
