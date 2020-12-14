<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilRAP extends Model
{
    use CompositePrimaryKey;
    
    protected $table = "detilrap";
    protected $primaryKey = ['id_retur', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_retur', 'id_barang', 'tgl_retur', 'qty', 'harga', 'diskon', 'diskonRp'];
    public $incrementing = false;

    public function retur() {
        return $this->belongsTo('App\Models\AP_Retur', 'id_retur', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
