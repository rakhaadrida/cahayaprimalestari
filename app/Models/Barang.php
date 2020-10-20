<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;
    protected $table = 'barang';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama', 'id_kategori', 'satuan', 'ukuran'];
    public $incrementing = false;

    public function jenis() {
        return $this->belongsTo('App\Models\JenisBarang', 'id_kategori', 'id');
    }

    public function hargaBarang() {
        return $this->hasMany('App\Models\HargaBarang', 'id_barang', 'id');
    }

    public function stokBarang() {
        return $this->hasMany('App\Models\StokBarang', 'id_barang', 'id');
    }

    public function detilpo() {
        return $this->hasMany('App\Models\DetilPO', 'id_barang', 'id');
    }

    public function detilbm() {
        return $this->hasMany('App\Models\DetilBM', 'id_barang', 'id');
    }

    public function detiltb() {
        return $this->hasMany('App\Models\DetilTB', 'id_barang', 'id');
    }

    public function detilso() {
        return $this->hasMany('App\Models\DetilSO', 'id_barang', 'id');
    }

    public function detil_approval() {
        return $this->hasMany('App\Models\DetilApproval', 'id_barang', 'id');
    }
}
