<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccReceivable extends Model
{
    protected $table = "ar";
    protected $primaryKey = "id_so";
    protected $keyType = "string";
    protected $fillable = ['id_so', 'tgl_bayar', 'cicil', 'retur', 'keterangan'];
    public $incrementing = false;

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_so', 'id');
    }
}
