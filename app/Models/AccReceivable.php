<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccReceivable extends Model
{
    protected $table = "ar";
    protected $primaryKey = "id";
    protected $keyType = "string";
    protected $fillable = ['id', 'id_so', 'retur', 'keterangan'];
    public $incrementing = false;

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_so', 'id');
    }

    public function detilar() {
        return $this->hasMany('App\Models\DetilAR', 'id_ar', 'id');
    }
}
