<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturJual extends Model
{
    protected $table = "returjual";
    protected $keyType = "string";
    protected $fillable = ['id', 'tanggal', 'id_customer', 'status', 'id_cabang'];
    public $incrementing = false;

    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'id_customer', 'id')->withTrashed();
    }

    public function cabang() {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id');
    }

    public function detilrj() {
        return $this->hasMany('App\Models\DetilRJ', 'id_retur', 'id');
    }
}
