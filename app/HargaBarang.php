<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CompositePrimaryKey;

class HargaBarang extends Model
{
    use SoftDeletes;
    use CompositePrimaryKey;

    protected $table = "hargabarang";
    protected $fillable = ['id_barang', 'id_harga', 'harga', 'ppn', 'harga_ppn'];
    protected $primaryKey = ['id_barang', 'id_harga'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function hargaBarang() {
        return $this->belongsTo('App\Harga', 'id_harga', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Barang', 'id_barang', 'id');
    }
}
