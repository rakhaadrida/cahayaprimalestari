<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratJalan extends Model
{
    use SoftDeletes;

    protected $table = "sj";
    protected $keyType = "string";
    // protected $fillable = ['id_so', 'tgl_sj', 'keterangan'];
    protected $fillable = ['id', 'tempo'];
    public $incrementing = false;
    
    // public function detilsj() {
    //     return $this->hasMany('App\Models\DetilSJ', 'id_so');
    // }

    // public function so() {
    //     return $this->belongsTo('App\Models\SalesOrder', 'id_so');
    // }
}
