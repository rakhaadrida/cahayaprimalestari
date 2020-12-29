<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilBM extends Model
{
    use CompositePrimaryKey;
    protected $table = "detilbm";
    protected $primaryKey = ['id_bm', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_bm', 'id_barang', 'harga', 'qty', 'diskon', 'disPersen'];

    public $incrementing = false;

    public function bm() {
        return $this->belongsTo('App\Models\BarangMasuk', 'id_bm', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
