<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilTB extends Model
{
    use CompositePrimaryKey;

    protected $table = "detiltb";
    protected $primaryKey = ['id_tb', 'id_barang', 'id_asal', 'id_tujuan'];
    protected $keyType = "string";
    protected $fillable = ['id_tb', 'id_barang', 'id_asal', 'id_tujuan', 'qty'];
    public $incrementing = false;

    public function tb() {
        return $this->belongsTo('App\TransferBarang', 'id_tb', 'id');
    }
}
