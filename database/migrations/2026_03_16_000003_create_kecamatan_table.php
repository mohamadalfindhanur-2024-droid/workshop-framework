<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->string('id', 7)->primary();
            $table->string('nama', 100);
            $table->string('id_kota', 4);
            $table->foreign('id_kota')->references('id')->on('kota');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
