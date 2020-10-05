<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CompositePrimaryKey;

class DetilSJ extends Model
{
    use CompositePrimaryKey;
    use SoftDeletes;

    protected $table = "detilsj";
    protected $primaryKey = ['id_so', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_so', 'id_barang', 'qtyRevisi', 'keterangan'];
    public $incrementing = false;

    public function sj() {
        return $this->belongsTo('App\Models\SuratJalan', 'id_so');
    }

    public function barang() {
        return $this->belongsTo('App\Models\SuratJalan', 'id_barang', 'id');
    }
}
