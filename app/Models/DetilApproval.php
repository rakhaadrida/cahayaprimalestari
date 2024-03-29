<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilApproval extends Model
{
    use CompositePrimaryKey;

    protected $table = "detil_approval";
    protected $primaryKey = ['id_app', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_app', 'id_barang', 'id_gudang', 'harga', 'qty', 'diskon'];

    public $incrementing = false;

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id')->withTrashed();
    }

    public function gudang() {
        return $this->belongsTo('App\Models\Gudang', 'id_gudang', 'id')->withTrashed();
    }

    // public function so() {
    //     return $this->belongsTo('App\Models\SalesOrder', 'id_so', 'id');
    // }

    public function approval() {
        return $this->belongsTo('App\Models\Approval', 'id_app', 'id');
    }
}
