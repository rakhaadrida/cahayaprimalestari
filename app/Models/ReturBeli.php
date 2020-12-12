<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturBeli extends Model
{
    protected $table = "returbeli";
    protected $keyType = "string";
    protected $fillable = ['id', 'tanggal', 'id_supplier', 'status'];
    public $incrementing = false;

    public function supplier() {
        return $this->belongsTo('App\Models\Supplier', 'id_supplier', 'id');
    }

    public function detilrb() {
        return $this->hasMany('App\Models\DetilRB', 'id_retur', 'id');
    }
}
