@extends('layouts.admin')

@section('title', 'Tambah Buku')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-variant"></i>
        </span> Tambah Buku
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Buku</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('buku.store') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <select class="form-control" id="idkategori" name="idkategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->idkategori }}" {{ old('idkategori') == $kategori->idkategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode Buku</label>
                        <input type="text" class="form-control" id="kode" name="kode" 
                               placeholder="Contoh: NV-01" value="{{ old('kode') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <input type="text" class="form-control" id="judul" name="judul" 
                               placeholder="Masukkan judul buku" value="{{ old('judul') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" class="form-control" id="pengarang" name="pengarang" 
                               placeholder="Masukkan nama pengarang" value="{{ old('pengarang') }}" required>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">
                        <i class="mdi mdi-content-save"></i> Simpan
                    </button>
                    <a href="{{ route('buku.index') }}" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
