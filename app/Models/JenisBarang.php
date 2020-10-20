<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisBarang extends Model
{
    use SoftDeletes;

    protected $table = "jenisbarang";
    protected $keyType = "string";
    protected $fillable = ['id', 'nama'];

    public $incrementing = false;

    public function barang() {
        return $this->hasMany('App\Models\Barang', 'id_kategori', 'id');
    }
}
