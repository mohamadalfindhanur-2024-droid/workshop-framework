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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-file-pdf text-danger"></i> Generate PDF Documents
                </h4>
                <p class="card-description">Buat dokumen PDF dengan format berbeda</p>
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-4 h-100">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-lg bg-gradient-info text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="mdi mdi-certificate mdi-24px"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Sertifikat</h5>
                                    <small class="text-muted">Format Landscape A4</small>
                                </div>
                            </div>
                            <p class="text-muted small">
                                Generate sertifikat dengan desain profesional dalam format landscape A4. 
                                Cocok untuk sertifikat penghargaan, kelulusan, atau partisipasi.
                            </p>
                            <a href="{{ route('export.sertifikat') }}" class="btn btn-gradient-info btn-block" target="_blank">
                                <i class="mdi mdi-download"></i> Download Sertifikat
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-4 h-100">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-lg bg-gradient-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="mdi mdi-email-open mdi-24px"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Surat Undangan</h5>
                                    <small class="text-muted">Format Portrait A4 dengan Header</small>
                                </div>
                            </div>
                            <p class="text-muted small">
                                Generate surat undangan resmi dengan kop surat dan format portrait A4. 
                                Dilengkapi dengan header dan tanda tangan elektronik.
                            </p>
                            <a href="{{ route('export.undangan') }}" class="btn btn-gradient-danger btn-block" target="_blank">
                                <i class="mdi mdi-download"></i> Download Undangan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
