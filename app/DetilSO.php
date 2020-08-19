<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilSO extends Model
{
    use CompositePrimaryKey;

    protected $table = "detilso";
    protected $primaryKey = ['id_so', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_so', 'id_barang', 'harga', 'qty', 'diskon'];

    public $incrementing = false;

    public function so() {
        return $this->belongsTo('App\SalesOrder', 'id_so');
    }

    public function barang() {
        return $this->belongsTo('App\Barang', 'id_barang', 'id');
    }
}
