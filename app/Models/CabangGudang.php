<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class CabangGudang extends Model
{
    protected $table = "cabang_gudang";
    protected $fillable = [
        'cabang_id',
        'gudang_id'
    ];

    public function cabang() {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id');
    }

    public function gudang() {
        return $this->belongsTo(Gudang::class, 'gudang_id', 'id');
    }
}
