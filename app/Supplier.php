<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $table = 'supplier';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama', 'alamat', 'telepon'];
    public $incrementing = false;

    public function po() {
        return $this->hasMany('App\PurchaseOrder', 'id_supplier', 'id');
    }

    public function bm() {
        return $this->hasMany('App\BarangMasuk', 'id_supplier', 'id');
    }
}
