<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;
    protected $table = 'po';

    public function supplier() {
        return $this->belongsTo('App\Supplier');
    }

    public function barang() {
        return $this->belongsToMany('App\Barang')->using('App\DetilPO');
    }
}
