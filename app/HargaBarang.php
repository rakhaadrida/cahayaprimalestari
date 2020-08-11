<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CompositePrimaryKey;

class HargaBarang extends Model
{
    use SoftDeletes;
    use CompositePrimaryKey;

    protected $table = "hargabarang";
    protected $fillable = ['id_barang', 'id_harga', 'harga'];
    protected $primaryKey = ['id_barang', 'id_harga'];
    protected $keyType = 'string';
    public $incrementing = false;
}
