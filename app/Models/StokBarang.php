<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CompositePrimaryKey;

class StokBarang extends Model
{
    use CompositePrimaryKey;
    use SoftDeletes;
    
    protected $table = "stok";
    protected $primaryKey = ['id_barang', 'id_gudang', 'status'];
    protected $keyType = "string";
    protected $fillable = ['id_barang', 'id_gudang', 'stok', 'status'];
    
    public $incrementing = false;

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id')->withTrashed();
    }

    public function gudang() {
        return $this->belongsTo('App\Models\Gudang', 'id_gudang', 'id')->withTrashed();
    }
}
