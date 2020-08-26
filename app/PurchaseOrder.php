<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;
    protected $table = 'po';
    protected $keyType = "string";
    protected $fillable = ['id', 'tgl_po', 'id_supplier', 'total', 'status'];

    public $incrementing = false;

    public function supplier() {
        return $this->belongsTo('App\Supplier', 'id_supplier', 'id');
    }

    public function detilpo() {
        return $this->hasMany('App\DetilPO', 'id_po', 'id');
    }
}
