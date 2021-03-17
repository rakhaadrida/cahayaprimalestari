<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class Approval extends Model
{
    use CompositePrimaryKey;
    
    protected $table = "approval";
    protected $primaryKey = ['id', 'id_dokumen'];
    protected $keyType = "string";
    protected $fillable = ['id', 'id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe',
                            'baca'];

    public $incrementing = false;

    public function need_approval() {
        return $this->belongsTo('App\Models\NeedApproval', 'id_dokumen', 'id_dokumen');
    }

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }

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
}
