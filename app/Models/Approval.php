<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $table = "approval";
    protected $keyType = "string";
    protected $fillable = ['id_so', 'tanggal', 'status', 'keterangan'];

    public $incrementing = false;

    public function need_approval() {
        return $this->belongsTo('App\Models\NeedApproval', 'id_so', 'id_so');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_so', 'id');
    }
}
