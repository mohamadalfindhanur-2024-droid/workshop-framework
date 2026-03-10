@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-bookmark-multiple"></i>
        </span> Edit Kategori
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Kategori</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="form-kategori-edit" action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST" class="forms-sample">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" 
                               placeholder="Masukkan nama kategori" 
                               value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                    </div>
                </form>
                <button type="button" id="btn-update" class="btn btn-gradient-primary me-2">
                    <span id="btn-update-text"><i class="mdi mdi-content-save"></i> Update</span>
                    <span id="btn-update-spinner" class="d-none">
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
    document.getElementById('btn-update').addEventListener('click', function() {
        const form = document.getElementById('form-kategori-edit');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        document.getElementById('btn-update-text').classList.add('d-none');
        document.getElementById('btn-update-spinner').classList.remove('d-none');
        this.disabled = true;
        form.submit();
    });
</script>
@endsection
