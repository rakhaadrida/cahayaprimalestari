<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faktur extends Model
{
    use SoftDeletes;

    protected $table = "faktur";
    protected $fillable = [
        'id', 
        'nomor', 
        'tanggal', 
        'total', 
        'status', 
        'id_user', 
        'id_cabang'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'id_user', 'id')->withTrashed();
    }

    public function cabang() {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id');
    }

    public function items() {
        return $this->hasMany(FakturItem::class, 'id_faktur', 'id');
    }
}