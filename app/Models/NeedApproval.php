<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NeedApproval extends Model
{
    protected $table = "need_approval";
    protected $keyType = "string";
    protected $fillable = ['id', 'tanggal', 'status', 'keterangan', 'id_dokumen', 'tipe', 'id_user'];

    public $incrementing = false;

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_dokumen', 'id');
    }

    public function bm() {
        return $this->belongsTo('App\Models\BarangMasuk', 'id_dokumen', 'id');
    }

    public function tb() {
        return $this->belongsTo('App\Models\TransferBarang', 'id_dokumen', 'id');
    }

    public function rj() {
        return $this->belongsTo('App\Models\ReturJual', 'id_dokumen', 'id');
    }

    public function rb() {
        return $this->belongsTo('App\Models\ReturBeli', 'id_dokumen', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }

    public function need_appdetil() {
        return $this->hasMany('App\Models\NeedAppDetil', 'id_app', 'id');
    }
}
