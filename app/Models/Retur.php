<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $table = "retur";
    protected $keyType = "string";
    protected $fillable = ['id', 'tanggal', 'id_faktur', 'tipe', 'status'];
    public $incrementing = false;

    public function bm() {
        return $this->belongsTo('App\Models\BarangMasuk', 'id_faktur', 'id');
    }

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_faktur', 'id');
    }

    public function detilretur() {
        return $this->hasMany('App\Models\DetilRetur', 'id_retur', 'id');
    }

    public function detilrj() {
        return $this->hasMany('App\Models\DetilRJ', 'id_retur', 'id');
    }

    public function detilrb() {
        return $this->hasMany('App\Models\DetilRB', 'id_retur', 'id');
    }
}
