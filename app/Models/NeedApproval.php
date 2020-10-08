<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class NeedApproval extends Model
{
    use CompositePrimaryKey;

    protected $table = "need_approval";
    protected $primaryKey = ['id_so', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_so', 'id_barang', 'harga', 'qty', 'diskon', 'status', 'keterangan'];

    public $incrementing = false;

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_so');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
