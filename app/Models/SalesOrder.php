<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use SoftDeletes;

    protected $table = "so";
    protected $keyType = "string";
    protected $fillable = ['id', 'tgl_so', 'tgl_kirim', 'total', 'kategori', 'tempo', 'pkp', 'status', 'id_customer'];

    public $incrementing = false;

    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'id_customer', 'id');
    }

    public function detilso() {
        return $this->hasMany('App\Models\DetilSO', 'id_so', 'id');
    }

    public function need_approval() {
        return $this->hasMany('App\Models\NeedApproval', 'id_dokumen', 'id');
    }

    public function approval() {
        return $this->hasMany('App\Models\Approval', 'id_dokumen', 'id');
    }

    public function detil_approval() {
        return $this->hasMany('App\Models\DetilApproval', 'id_so', 'id');
    }

    public function ar() {
        return $this->hasOne('App\Models\AccReceivable', 'id_so', 'id');
    }
}
