<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CompositePrimaryKey;

class DetilPO extends Model
{
    use SoftDeletes;
    use CompositePrimaryKey;

    protected $table = "detilpo";
    protected $primaryKey = ['id_po', 'id_barang', 'id_harga'];
    protected $keyType = "string";

    public $incrementing = "false";

    // public function po() {
    //     return $this->belongsTo('App\PurchaseOrder');
    // }
}
