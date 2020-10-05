<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangMasuk extends Model
{
    use SoftDeletes;
    protected $table = "barangmasuk";
    protected $keyType = "string";
    protected $fillable = ['id', 'tanggal', 'id_supplier', 'status'];
    public $incrementing = false;

    public function supplier() {
        return $this->belongsTo('App\Models\Supplier', 'id_supplier', 'id');
    }
}
