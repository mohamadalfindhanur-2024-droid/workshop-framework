<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'total',
        'status_order',
        'metode_pembayaran',
        'bank_va',
        'payment_code',
        'payment_payload',
        'expires_at',
        'paid_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class, 'id_transaksi', 'id');
    }
}
