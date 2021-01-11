<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilRT extends Model
{
    use CompositePrimaryKey;

    protected $table = "detilrt";
    protected $primaryKey = ['id_terima', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_terima', 'id_barang', 'qty_terima', 'qty_batal', 'potong'];

    public $incrementing = false;

    public function returterima() {
        return $this->belongsTo('App\Models\ReturTerima', 'id_terima', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
