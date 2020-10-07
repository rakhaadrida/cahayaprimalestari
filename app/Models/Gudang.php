<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gudang extends Model
{
    use SoftDeletes;
    protected $table = 'gudang';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama', 'alamat'];
    public $incrementing = false;

    public function stokBarang() {
        return $this->hasMany('App\Models\StokBarang', 'id_gudang', 'id');
    }
}