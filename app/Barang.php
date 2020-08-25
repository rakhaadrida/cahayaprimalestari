<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;
    protected $table = 'barang';

    protected $fillable = ['id', 'nama', 'ukuran', 'isi'];

    public function po() {
        return $this->belongsToMany('App\PurchaseOrder')->using('App\DetilPO');
    }

    public function hargaBarang() {
        return $this->hasMany('App\HargaBarang', 'id_barang', 'id');
    }

    public function stokBarang() {
        return $this->hasMany('App\StokBarang', 'id_barang', 'id');
    }

    public function detilpo() {
        return $this->hasMany('App\DetilPO', 'id_barang', 'id');
    }
}
