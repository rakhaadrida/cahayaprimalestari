<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangMasuk extends Model
{
    use SoftDeletes;
    protected $table = "barangmasuk";
    protected $keyType = "string";
    protected $fillable = ['id', 'tanggal', 'total', 'id_gudang', 'id_supplier', 'status'];
    public $incrementing = false;

    public function gudang() {
        return $this->belongsTo('App\Models\Gudang', 'id_gudang', 'id');
    }

    public function supplier() {
        return $this->belongsTo('App\Models\Supplier', 'id_supplier', 'id');
    }

    public function detilbm() {
        return $this->hasMany('App\Models\DetilBM', 'id_bm', 'id');
    }

    public function need_approval() {
        return $this->hasMany('App\Models\NeedApproval', 'id_dokumen', 'id');
    }

    public function ap() {
        return $this->hasOne('App\Models\AccPayable', 'id_bm', 'id');
    }

    public function approval() {
        return $this->hasMany('App\Models\Approval', 'id_dokumen', 'id');
    }
}
