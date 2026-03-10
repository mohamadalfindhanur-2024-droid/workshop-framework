@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-bookmark-multiple"></i>
        </span> Tambah Kategori
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Kategori</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="form-kategori-create" action="{{ route('kategori.store') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" 
                               placeholder="Masukkan nama kategori" value="{{ old('nama_kategori') }}" required>
                    </div>
                </form>
                <button type="button" id="btn-simpan" class="btn btn-gradient-primary me-2">
                    <span id="btn-simpan-text"><i class="mdi mdi-content-save"></i> Simpan</span>
                    <span id="btn-simpan-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
                <a href="{{ route('kategori.index') }}" class="btn btn-light">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script>
    document.getElementById('btn-simpan').addEventListener('click', function() {
        const form = document.getElementById('form-kategori-create');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        document.getElementById('btn-simpan-text').classList.add('d-none');
        document.getElementById('btn-simpan-spinner').classList.remove('d-none');
        this.disabled = true;
        form.submit();
    });
</script>
@endsection
