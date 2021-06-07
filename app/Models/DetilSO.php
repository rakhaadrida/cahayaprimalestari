<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilSO extends Model
{
    use CompositePrimaryKey;

    protected $table = "detilso";
    protected $primaryKey = ['id_so', 'id_barang', 'id_gudang'];
    protected $keyType = "string";
    protected $fillable = ['id_so', 'id_barang', 'id_gudang', 'harga', 'qty', 'diskon', 'diskonRp'];

    public $incrementing = false;

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_so', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id')->withTrashed();
    }

    public function gudang() {
        return $this->belongsTo('App\Models\Gudang', 'id_gudang', 'id')->withTrashed();
    }
}
