<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Harga extends Model
{
    use SoftDeletes;
    protected $table = 'harga';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama'];
    public $incrementing = false;

    public function hargaBarang() {
        return $this->hasMany('App\Models\HargaBarang', 'id_harga', 'id');
    }
}
