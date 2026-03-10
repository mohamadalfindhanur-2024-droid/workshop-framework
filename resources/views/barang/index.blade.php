@extends('layouts.admin')

@section('title', 'Data Barang')

@section('style_page')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-package-variant"></i>
        </span> Data Barang UMKM
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Barang</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cetak Label Harga - Format TnJ 108</h4>
                <p class="card-description"> 
                    Pilih barang yang ingin dicetak labelnya, tentukan posisi awal cetak (X,Y), lalu klik tombol cetak.
                </p>
                
                <form id="form-cetak-label" action="{{ route('barang.cetak') }}" method="POST" target="_blank">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-gradient-primary btn-icon-text">
                                <i class="mdi mdi-printer btn-icon-prepend"></i>
                                Cetak Label yang Dipilih
                            </button>
                            <button type="button" id="pilih-semua" class="btn btn-gradient-info btn-icon-text">
                                <i class="mdi mdi-checkbox-multiple-marked btn-icon-prepend"></i>
                                Pilih Semua
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-items-center">
                                <label class="mb-0 me-2"><strong>Posisi Awal Cetak:</strong></label>
                                <div class="me-2">
                                    <label class="mb-0 me-1">X (Kolom):</label>
                                    <input type="number" name="koordinat_x" class="form-control form-control-sm d-inline-block" 
                                           value="1" min="1" max="5" style="width: 70px;">
                                </div>
                                <div>
                                    <label class="mb-0 me-1">Y (Baris):</label>
                                    <input type="number" name="koordinat_y" class="form-control form-control-sm d-inline-block" 
                                           value="1" min="1" max="8" style="width: 70px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabel-barang">
                            <thead>
                                <tr>
                                    <th width="50">Pilih</th> 
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Waktu Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barang as $item)
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input checkbox-barang" 
                                                   name="cetak[]" value="{{ $item->id_barang }}" id="check-{{ $item->id_barang }}">
                                            <label class="form-check-label" for="check-{{ $item->id_barang }}"></label>
                                        </div>
                                    </td>
                                    <td><strong>{{ $item->id_barang }}</strong></td>
                                    <td>{{ $item->nama }}</td>
                                    <td>
                                        <span class="badge badge-gradient-warning">
                                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>{{ $item->timestamp }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data barang</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#tabel-barang').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "pageLength": 10,
                "order": [[1, 'asc']]
            });
            
            // Tombol Pilih Semua
            let allSelected = false;
            $('#pilih-semua').click(function() {
                allSelected = !allSelected;
                $('.checkbox-barang').prop('checked', allSelected);
                
                // Update button text dan style
                if(allSelected) {
                    $(this).html('<i class="mdi mdi-checkbox-blank-outline btn-icon-prepend"></i> Batal Pilih Semua');
                    $(this).removeClass('btn-gradient-info').addClass('btn-gradient-secondary');
                } else {
                    $(this).html('<i class="mdi mdi-checkbox-multiple-marked btn-icon-prepend"></i> Pilih Semua');
                    $(this).removeClass('btn-gradient-secondary').addClass('btn-gradient-info');
                }
            });
            
            // Validasi form sebelum submit
            $('#form-cetak-label').submit(function(e) {
                var checked = $('.checkbox-barang:checked').length;
                
                if(checked === 0) {
                    e.preventDefault();
                    alert('Pilih minimal 1 barang untuk dicetak!');
                    return false;
                }
                
                return true;
            });
        });
    </script>
            });
        });
    </script>
@endsection