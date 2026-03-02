@extends('layouts.admin')

@section('style_page')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Barang UMKM</h4>
                <p class="card-description"> 
                    Daftar barang beserta harga yang siap dicetak ke Label TnJ 108.
                </p>
                
                <form id="form-cetak-label" action="{{ route('barang.cetak') }}" method="POST" target="_blank">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-gradient-primary btn-icon-text">
                                <i class="mdi mdi-printer btn-icon-prepend"></i>
                                Cetak Label yang Dipilih
                            </button>
                            <button type="button" id="pilih-semua" class="btn btn-outline-secondary btn-icon-text">
                                <i class="mdi mdi-checkbox-multiple-marked btn-icon-prepend"></i>
                                Pilih Semua
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="form-inline float-right">
                                <label class="mr-2">Posisi Awal Cetak:</label>
                                <div class="form-group mr-2">
                                    <label class="mr-1">X (Kolom):</label>
                                    <input type="number" name="koordinat_x" class="form-control form-control-sm" 
                                           value="1" min="1" max="5" style="width: 60px;">
                                </div>
                                <div class="form-group">
                                    <label class="mr-1">Y (Baris):</label>
                                    <input type="number" name="koordinat_y" class="form-control form-control-sm" 
                                           value="1" min="1" max="8" style="width: 60px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped" id="tabel-barang">
                            <thead>
                                <tr>
                                    <th>Pilih</th> 
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Waktu Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="checkbox-barang" name="cetak[]" value="{{ $item->id_barang }}">
                                    </td>
                                    <td>{{ $item->id_barang }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ $item->timestamp }}</td>
                                </tr>
                                @endforeach
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
                }
            });
            
            // Tombol Pilih Semua
            $('#pilih-semua').click(function() {
                var checkboxes = $('.checkbox-barang');
                var isAllChecked = checkboxes.filter(':checked').length === checkboxes.length;
                
                checkboxes.prop('checked', !isAllChecked);
                
                // Update text tombol
                if (!isAllChecked) {
                    $(this).html('<i class="mdi mdi-checkbox-blank-outline btn-icon-prepend"></i> Batal Pilih Semua');
                } else {
                    $(this).html('<i class="mdi mdi-checkbox-multiple-marked btn-icon-prepend"></i> Pilih Semua');
                }
            });
            
            // Validasi sebelum submit
            $('#form-cetak-label').submit(function(e) {
                var checkedBoxes = $('.checkbox-barang:checked');
                
                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal 1 barang untuk dicetak!');
                    return false;
                }
                
                return true;
            });
        });
    </script>
@endsection