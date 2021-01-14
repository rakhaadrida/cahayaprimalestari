<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilRJ extends Model
{
    use CompositePrimaryKey;
    
    protected $table = "detilrj";
    protected $primaryKey = ['id_retur', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_retur', 'id_barang', 'id_kirim', 'tgl_kirim', 'qty_retur', 'qty_kirim', 'potong'];

    public $incrementing = false;

    public function retur() {
        return $this->belongsTo('App\Models\ReturJual', 'id_retur', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
