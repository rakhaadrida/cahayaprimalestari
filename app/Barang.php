<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;
    protected $table = 'barang';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama', 'ukuran', 'isi'];
    public $incrementing = false;

    public function hargaBarang() {
        return $this->hasMany('App\HargaBarang', 'id_barang', 'id');
    }

    public function stokBarang() {
        return $this->hasMany('App\StokBarang', 'id_barang', 'id');
    }

    public function detilpo() {
        return $this->hasMany('App\DetilPO', 'id_barang', 'id');
    }

    public function detilbm() {
        return $this->hasMany('App\DetilBM', 'id_barang', 'id');
    }

    public function detiltb() {
        return $this->hasMany('App\DetilTB', 'id_barang', 'id');
    }

    public function detilso() {
        return $this->hasMany('App\DetilSO', 'id_barang', 'id');
    }
}
