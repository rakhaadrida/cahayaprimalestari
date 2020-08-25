<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Harga extends Model
{
    use SoftDeletes;
    protected $table = 'harga';

    protected $fillable = ['id', 'nama'];

    public function barang() {
        return $this->belongsToMany('App\Barang')->using('App\HargaBarang');
    }

    public function hargaBarang() {
        return $this->hasMany('App\HargaBarang', 'id_harga', 'id');
    }
}
