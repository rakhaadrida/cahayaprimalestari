<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilRB extends Model
{
    use CompositePrimaryKey;
    
    protected $table = "detilrb";
    protected $primaryKey = ['id_retur', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_retur', 'id_barang', 'qty_retur'];

    public $incrementing = false;

    public function returbeli() {
        return $this->belongsTo('App\Models\ReturBeli', 'id_retur', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
