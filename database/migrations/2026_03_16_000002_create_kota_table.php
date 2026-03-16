<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kota', function (Blueprint $table) {
            $table->string('id', 4)->primary();
            $table->string('nama', 100);
            $table->string('id_provinsi', 2);
            $table->foreign('id_provinsi')->references('id')->on('provinsi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kota');
    }
};
