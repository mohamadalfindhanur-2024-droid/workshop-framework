@extends('layouts.admin')

@section('title', 'Data Buku')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-variant"></i>
        </span> Data Buku
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buku</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Daftar Buku</h4>
                    <div>
                        <a href="{{ route('export.buku') }}" class="btn btn-gradient-danger btn-sm me-2">
                            <i class="mdi mdi-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Tambah Buku
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
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukus as $index => $buku)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $buku->kode }}</strong></td>
                                <td>{{ $buku->judul }}</td>
                                <td>{{ $buku->pengarang }}</td>
                                <td>
                                    <span class="badge badge-gradient-info">
                                        {{ $buku->kategori->nama_kategori }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('export.buku.detail', $buku->idbuku) }}" class="btn btn-gradient-danger btn-sm" title="Export PDF">
                                        <i class="mdi mdi-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('buku.edit', $buku->idbuku) }}" class="btn btn-gradient-info btn-sm">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('buku.destroy', $buku->idbuku) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-gradient-danger btn-sm" 
                                                onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data buku</td>
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
