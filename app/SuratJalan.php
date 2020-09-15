<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratJalan extends Model
{
    use SoftDeletes;

    protected $table = "sj";
    protected $keyType = "string";
    protected $fillable = ['id_so', 'tgl_sj', 'keterangan'];
    public $incrementing = false;
    
    public function detilsj() {
        return $this->hasMany('App\DetilSJ', 'id_so');
    }

    public function so() {
        return $this->belongsTo('App\SalesOrder', 'id_so');
    }
}
