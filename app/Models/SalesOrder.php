<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use SoftDeletes;

    protected $table = "so";
    protected $keyType = "string";
    protected $fillable = ['id', 'tgl_so', 'tgl_kirim', 'total', 'diskon', 'kategori', 'tempo', 'pkp', 'status', 'id_customer', 'id_user'];

    public $incrementing = false;

    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'id_customer', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }

    public function detilso() {
        return $this->hasMany('App\Models\DetilSO', 'id_so', 'id');
    }

    public function tandaterima() {
        return $this->hasMany('App\Models\TandaTerima', 'id_so', 'id');
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
    
    // public function retur() {
    //     return $this->hasMany('App\Models\Retur', 'id_faktur', 'id');
    // }
}