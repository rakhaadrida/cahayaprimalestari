<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferBarang extends Model
{
    protected $table = "transferbarang";
    protected $keyType = "string";
    protected $fillable = ['id', 'tgl_tb'];
    public $incrementing = false;

    public function detiltb() {
        return $this->hasMany('App\DetilTB', 'id_tb', 'id');
    }
}
