@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Dashboard
    </h3>
</div>

<div class="row">
    <div class="col-md-6 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Kategori <i class="mdi mdi-bookmark-multiple mdi-24px float-end"></i></h4>
                <h2 class="mb-5">{{ \App\Models\Kategori::count() }}</h2>
                <h6 class="card-text">Kategori Buku</h6>
            </div>
        </div>
    </div>
    <div class="col-md-6 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Buku <i class="mdi mdi-book-open-variant mdi-24px float-end"></i></h4>
                <h2 class="mb-5">{{ \App\Models\Buku::count() }}</h2>
                <h6 class="card-text">Koleksi Buku</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h4>
                <p>Aplikasi Perpustakaan dengan Laravel & Purple Admin Template</p>
                <p>Silakan pilih menu di sidebar untuk mengelola data.</p>
            </div>
        </div>
    </div>
</div>
@endsection
