<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('nama', 100);
            $table->string('id_kecamatan', 7);
            $table->foreign('id_kecamatan')->references('id')->on('kecamatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelurahan');
    }
};
