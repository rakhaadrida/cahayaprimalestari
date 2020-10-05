<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferBarang extends Model
{
    protected $table = "transferbarang";
    protected $keyType = "string";
    protected $fillable = ['id', 'tgl_tb'];
    public $incrementing = false;

    public function detiltb() {
        return $this->hasMany('App\Models\DetilTB', 'id_tb', 'id');
    }
}
