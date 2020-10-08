<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilApproval extends Model
{
    use CompositePrimaryKey;

    protected $table = "detil_approval";
    protected $primaryKey = ['id_so', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_so', 'id_barang', 'harga', 'qty', 'diskon'];

    public $incrementing = false;

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_so', 'id');
    }

    public function approval() {
        return $this->belongsTo('App\Models\Approval', 'id_so', 'id_so');
    }
}
