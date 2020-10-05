<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $table = 'supplier';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama', 'alamat', 'telepon', 'npwp'];
    public $incrementing = false;

    public function po() {
        return $this->hasMany('App\Models\PurchaseOrder', 'id_supplier', 'id');
    }

    public function bm() {
        return $this->hasMany('App\Models\BarangMasuk', 'id_supplier', 'id');
    }
}
