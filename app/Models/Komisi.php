<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
    protected $table = 'komisi';
    protected $keyType = 'string';
    protected $primaryKey = 'bulan';
    protected $fillable = ['bulan', 'tanggal', 'nama', 'file'];
    public $incrementing = false;

    public function getFileAttribute($value) {
        return url('/storage/' . $value);
    }
}
