<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;
    protected $table = 'barang';

    protected $fillable = ['id', 'nama', 'ukuran', 'isi'];

    public function supplier() {
        return $this->belongsToMany('App\Supplier');
    }

    public function harga() {
        return $this->belongsToMany('App\Harga');
    }
}
