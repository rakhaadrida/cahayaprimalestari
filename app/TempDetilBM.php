<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class TempDetilBM extends Model
{
    use CompositePrimaryKey;

    protected $table = "temp_detilbm";
    protected $primaryKey = ['id_bm', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_bm', 'id_barang', 'harga', 'qty', 'keterangan', 'id_supplier'];

    public $incrementing = false;

    public function bm() {
        return $this->belongsTo('App\BarangMasuk', 'id_bm', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Barang', 'id_barang', 'id');
    }

    public function supplier() {
        return $this->belongsTo('App\Supplier', 'id_supplier', 'id');
    }
}
