<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Utilities\Traits\FilterByWarehouseType;

class Gudang extends Model
{
    use SoftDeletes, FilterByWarehouseType;

    protected $table = 'gudang';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama', 'alamat', 'tipe'];
    public $incrementing = false;

    public function stokBarang() {
        return $this->hasMany('App\Models\StokBarang', 'id_gudang', 'id');
    }

    public function detil_approval() {
        return $this->hasMany('App\Models\DetilApproval', 'id_gudang', 'id');
    }

    public function tbAsal() {
        return $this->hasMany('App\Models\DetilTB', 'id_asal', 'id');
    }

    public function tbTuju() {
        return $this->hasMany('App\Models\DetilTB', 'id_tujuan', 'id');
    }
}
