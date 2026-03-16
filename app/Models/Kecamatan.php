<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['id', 'nama', 'id_kota'];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id');
    }

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, 'id_kecamatan', 'id');
    }
}
