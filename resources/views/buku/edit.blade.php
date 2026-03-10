@extends('layouts.admin')

@section('title', 'Edit Buku')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-variant"></i>
        </span> Edit Buku
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Buku</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="form-buku-edit" action="{{ route('buku.update', $buku->idbuku) }}" method="POST" class="forms-sample">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <select class="form-control" id="idkategori" name="idkategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->idkategori }}" 
                                        {{ old('idkategori', $buku->idkategori) == $kategori->idkategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode Buku</label>
                        <input type="text" class="form-control" id="kode" name="kode" 
                               placeholder="Contoh: NV-01" 
                               value="{{ old('kode', $buku->kode) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <input type="text" class="form-control" id="judul" name="judul" 
                               placeholder="Masukkan judul buku" 
                               value="{{ old('judul', $buku->judul) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" class="form-control" id="pengarang" name="pengarang" 
                               placeholder="Masukkan nama pengarang" 
                               value="{{ old('pengarang', $buku->pengarang) }}" required>
                    </div>
                </form>
                <button type="button" id="btn-update" class="btn btn-gradient-primary me-2">
                    <span id="btn-update-text"><i class="mdi mdi-content-save"></i> Update</span>
                    <span id="btn-update-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
                <a href="{{ route('buku.index') }}" class="btn btn-light">
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
        const form = document.getElementById('form-buku-edit');
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
