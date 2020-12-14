<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AP_Retur extends Model
{
    protected $table = "ap_retur";
    protected $keyType = "string";
    protected $fillable = ['id', 'id_ap', 'tanggal', 'total', 'id_user'];
    public $incrementing = false;

    public function ar() {
        return $this->belongsTo('App\Models\AccPayable', 'id_ap', 'id');
    }

    public function detilrap() {
        return $this->hasMany('App\Models\DetilRAP', 'id_retur',  'id');
    }
}
