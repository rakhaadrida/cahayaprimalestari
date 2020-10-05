<?php

namespace App\Models;

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
        return $this->belongsTo('App\Models\Supplier', 'id_supplier', 'id');
    }

    public function detilpo() {
        return $this->hasMany('App\Models\DetilPO', 'id_po', 'id');
    }
}
