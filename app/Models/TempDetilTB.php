<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class TempDetilTB extends Model
{
    use CompositePrimaryKey;

    protected $table = "temp_detiltb";
    protected $primaryKey = ['id_tb', 'id_barang', 'id_asal', 'id_tujuan'];
    protected $keyType = "string";
    protected $fillable = ['id_tb', 'id_barang', 'id_asal', 'id_tujuan', 'stok_asal', 'stok_tujuan', 'qty'];
    public $incrementing = false;

    public function tb() {
        return $this->belongsTo('App\Models\TransferBarang', 'id_tb', 'id');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }

    public function gudangAsal() {
        return $this->belongsTo('App\Models\Gudang', 'id_asal', 'id');
    }

    public function gudangTujuan() {
        return $this->belongsTo('App\Models\Gudang', 'id_tujuan', 'id');
    }
}
