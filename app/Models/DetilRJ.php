<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilRJ extends Model
{
    use CompositePrimaryKey;
    
    protected $table = "detilrj";
    protected $primaryKey = ['id_retur', 'id_barang', 'id_kirim'];
    protected $keyType = "string";
    protected $fillable = ['id_retur', 'id_barang', 'id_kirim', 'tgl_kirim', 'qty_kirim', 'qty_batal'];

    public $incrementing = false;

    public function retur() {
        return $this->belongsTo('App\Models\Retur', 'id_retur', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
