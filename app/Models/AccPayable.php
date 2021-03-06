<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccPayable extends Model
{
    protected $table = "ap";
    protected $primaryKey = "id";
    protected $keyType = "string";
    protected $fillable = ['id', 'id_bm', 'keterangan'];

    public $incrementing = false;

    public function bm() {
        return $this->hasMany('App\Models\BarangMasuk', 'id_faktur', 'id_bm');
    }

    public function detilap() {
        return $this->hasMany('App\Models\DetilAP', 'id_ap', 'id');
    }

    public function retur() {
        return $this->hasMany('App\Models\AP_Retur', 'id_ap', 'id');
    }
}
