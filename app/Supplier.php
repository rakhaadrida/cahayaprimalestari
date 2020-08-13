<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $table = 'supplier';

    protected $fillable = ['id', 'nama', 'alamat', 'telepon'];

    public function po() {
        return $this->hasMany('App\PurchaseOrder', 'id_supplier', 'id');
    }
}
