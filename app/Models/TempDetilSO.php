<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class TempDetilSO extends Model
{
    use CompositePrimaryKey;

    protected $table = "temp_detilso";
    protected $primaryKey = ['id_so', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_so', 'id_barang', 'harga', 'qty', 'diskon', 'id_customer', 'tempo', 'status', 'keterangan'];

    public $incrementing = false;

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_so');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }

    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'id_customer', 'id');
    }
}
