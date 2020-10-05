<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class TempDetilPO extends Model
{
    use CompositePrimaryKey;

    protected $table = "temp_detilpo";
    protected $primaryKey = ['id_po', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_po', 'id_barang', 'harga', 'qty'];

    public $incrementing = false;

    public function po() {
        return $this->belongsTo('App\Models\PurchaseOrder', 'id_po', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }

    public function supplier() {
        return $this->belongsTo('App\Models\Supplier', 'id_supplier', 'id');
    }
}
