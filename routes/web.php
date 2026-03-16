<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\KasirController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Google OAuth Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// OTP Verification Routes
Route::get('auth/verify-otp', [GoogleController::class, 'showOTPForm'])->name('otp.verify.form');
Route::post('auth/verify-otp', [GoogleController::class, 'verifyOTP'])->name('otp.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Barang Routes
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang/cetak-label', [BarangController::class, 'cetakLabel'])->name('barang.cetak');
    Route::get('/barang/form-html', [BarangController::class, 'formHtml'])->name('barang.form-html');
    Route::get('/barang/form-datatable', [BarangController::class, 'formDatatable'])->name('barang.form-datatable');
    
    // Kategori Routes
    Route::resource('kategori', KategoriController::class);
    
    // Buku Routes
    Route::resource('buku', BukuController::class);
    
    // PDF Export Routes
    Route::get('export/buku', [PDFController::class, 'exportBuku'])->name('export.buku');
    Route::get('export/buku/{id}', [PDFController::class, 'exportBukuDetail'])->name('export.buku.detail');
    Route::get('export/kategori', [PDFController::class, 'exportKategori'])->name('export.kategori');
    Route::get('export/kategori/{id}', [PDFController::class, 'exportKategoriDetail'])->name('export.kategori.detail');
    Route::get('export/sertifikat', [PDFController::class, 'exportSertifikat'])->name('export.sertifikat');
    Route::get('export/undangan', [PDFController::class, 'exportUndangan'])->name('export.undangan');
    
    // Logout Route
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    // Kota Select Route
    Route::get('/kota', function () {
        return view('kota.index');
    })->name('kota.index');

    // Wilayah Routes
    Route::get('/wilayah/ajax',  [WilayahController::class, 'ajax'])->name('wilayah.ajax');
    Route::get('/wilayah/axios', [WilayahController::class, 'axios'])->name('wilayah.axios');
    Route::get('/wilayah/kota',      [WilayahController::class, 'getKota'])->name('wilayah.kota');
    Route::get('/wilayah/kecamatan', [WilayahController::class, 'getKecamatan'])->name('wilayah.kecamatan');
    Route::get('/wilayah/kelurahan', [WilayahController::class, 'getKelurahan'])->name('wilayah.kelurahan');

    // Kasir Routes
    Route::get('/kasir/ajax',  [KasirController::class, 'ajax'])->name('kasir.ajax');
    Route::get('/kasir/axios', [KasirController::class, 'axios'])->name('kasir.axios');
    Route::get('/kasir/cari-barang', [KasirController::class, 'cariBarang'])->name('kasir.cari-barang');
    Route::post('/kasir/bayar',      [KasirController::class, 'bayar'])->name('kasir.bayar');
});