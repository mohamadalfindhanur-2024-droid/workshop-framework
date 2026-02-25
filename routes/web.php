<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PDFController;

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
    
    // Kategori Routes
    Route::resource('kategori', KategoriController::class);
    
    // Buku Routes
    Route::resource('buku', BukuController::class);
    
    // PDF Export Routes
    Route::get('export/buku', [PDFController::class, 'exportBuku'])->name('export.buku');
    Route::get('export/buku/{id}', [PDFController::class, 'exportBukuDetail'])->name('export.buku.detail');
    Route::get('export/kategori', [PDFController::class, 'exportKategori'])->name('export.kategori');
    Route::get('export/kategori/{id}', [PDFController::class, 'exportKategoriDetail'])->name('export.kategori.detail');
    
    // Logout Route
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});