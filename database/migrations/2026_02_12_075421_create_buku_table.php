<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('buku', function (Blueprint $table) {
        $table->integer('idbuku')->autoIncrement(); // Primary Key
        $table->string('kode', 20);
        $table->string('judul', 500);
        $table->string('pengarang', 200);
        
        // Menghubungkan ke tabel kategori (Foreign Key)
        $table->integer('idkategori'); 
        $table->foreign('idkategori')->references('idkategori')->on('kategori');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
