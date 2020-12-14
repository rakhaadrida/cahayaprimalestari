<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AR_Retur extends Model
{
    protected $table = "ar_retur";
    protected $keyType = "string";
    protected $fillable = ['id', 'id_ar', 'tanggal', 'total', 'id_user'];
    public $incrementing = false;

    public function ar() {
        return $this->belongsTo('App\Models\AccReceivable', 'id_ar', 'id');
    }

    public function detilrar() {
        return $this->hasMany('App\Models\DetilRAR', 'id_retur',  'id');
    }
}
