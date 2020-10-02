<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use SoftDeletes;

    protected $table = "so";
    protected $keyType = "string";
    protected $fillable = ['id', 'tgl_so', 'tgl_kirim', 'total', 'tempo', 'pkp', 'status', 'keterangan', 'id_customer'];

    public $incrementing = false;

    public function customer() {
        return $this->belongsTo('App\Customer', 'id_customer', 'id');
    }

    public function detilso() {
        return $this->hasMany('App\DetilSO', 'id_so', 'id');
    }

    public function tempdetilso() {
        return $this->hasMany('App\TempDetilSO', 'id_so', 'id');
    }
}
