<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('kode_transaksi', 50)->nullable()->unique()->after('id');
            $table->string('status_order', 20)->default('pending')->after('total');
            $table->string('metode_pembayaran', 20)->nullable()->after('status_order');
            $table->string('bank_va', 20)->nullable()->after('metode_pembayaran');
            $table->string('payment_code', 100)->nullable()->after('bank_va');
            $table->text('payment_payload')->nullable()->after('payment_code');
            $table->timestamp('expires_at')->nullable()->after('payment_payload');
            $table->timestamp('paid_at')->nullable()->after('expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropUnique(['kode_transaksi']);
            $table->dropColumn([
                'kode_transaksi',
                'status_order',
                'metode_pembayaran',
                'bank_va',
                'payment_code',
                'payment_payload',
                'expires_at',
                'paid_at',
            ]);
        });
    }
};
