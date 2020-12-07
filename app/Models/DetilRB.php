<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilRB extends Model
{
    use CompositePrimaryKey;
    
    protected $table = "detilrb";
    protected $primaryKey = ['id_retur', 'id_barang', 'id_terima'];
    protected $keyType = "string";
    protected $fillable = ['id_retur', 'id_barang', 'id_terima', 'tgl_terima', 'qty_terima', 'qty_batal'];

    public $incrementing = false;

    public function retur() {
        return $this->belongsTo('App\Models\Retur', 'id_retur', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
