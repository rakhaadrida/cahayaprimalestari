<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccPayable extends Model
{
    protected $table = "ap";
    protected $primaryKey = "id_bm";
    protected $keyType = "string";
    protected $fillable = ['id_bm', 'tgl_bayar', 'transfer', 'keterangan'];

    public $incrementing = false;

    public function bm() {
        return $this->belongsTo('App\Models\BarangMasuk', 'id_bm', 'id');
    }
}
