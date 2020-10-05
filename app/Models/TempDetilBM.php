<?php

namespace App\Models;

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
        return $this->belongsTo('App\Models\BarangMasuk', 'id_bm', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }

    public function supplier() {
        return $this->belongsTo('App\Models\Supplier', 'id_supplier', 'id');
    }
}
