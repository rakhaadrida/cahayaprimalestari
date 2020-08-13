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

    public function harga() {
        return $this->belongsToMany('App\Harga')->using('App\HargaBarang');
    }

    public function detilpo() {
        return $this->hasMany('App\DetilPO', 'id_barang', 'id');
    }
}
