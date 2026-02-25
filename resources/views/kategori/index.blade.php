@extends('layouts.admin')

@section('title', 'Data Kategori')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-bookmark-multiple"></i>
        </span> Data Kategori
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kategori</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Daftar Kategori</h4>
                    <div>
                        <a href="{{ route('export.kategori') }}" class="btn btn-gradient-danger btn-sm me-2">
                            <i class="mdi mdi-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Tambah Kategori
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Jumlah Buku</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $index => $kategori)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>{{ $kategori->buku->count() }} buku</td>
                                <td>
                                    <a href="{{ route('export.kategori.detail', $kategori->idkategori) }}" class="btn btn-gradient-danger btn-sm" title="Export PDF">
                                        <i class="mdi mdi-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('kategori.edit', $kategori->idkategori) }}" class="btn btn-gradient-info btn-sm">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('kategori.destroy', $kategori->idkategori) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-gradient-danger btn-sm" 
                                                onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data kategori</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
