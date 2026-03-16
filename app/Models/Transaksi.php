<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = ['tanggal', 'total'];

    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class, 'id_transaksi', 'id');
    }
}
