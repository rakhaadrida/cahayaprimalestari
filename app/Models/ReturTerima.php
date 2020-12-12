<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturTerima extends Model
{
    protected $table = "returterima";
    protected $keyType = "string";
    protected $fillable = ['id', 'id_retur', 'tanggal'];
    public $incrementing = false;

    public function returbeli() {
        return $this->belongsTo('App\Models\ReturBeli', 'id_retur', 'id');
    }

    public function detilrt() {
        return $this->hasMany('App\Models\DetilRT', 'id_terima', 'id');
    }
}
