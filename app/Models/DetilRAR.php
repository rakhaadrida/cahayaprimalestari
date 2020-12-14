<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilRAR extends Model
{
    use CompositePrimaryKey;
    
    protected $table = "detilrar";
    protected $primaryKey = ['id_retur', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_retur', 'id_barang', 'tgl_retur', 'qty', 'harga', 'diskon', 'diskonRp'];
    public $incrementing = false;

    public function retur() {
        return $this->belongsTo('App\Models\AR_Retur', 'id_retur', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
