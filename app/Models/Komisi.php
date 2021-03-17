<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
    protected $table = 'komisi';
    protected $keyType = 'string';
    protected $primaryKey = 'bulan';
    protected $fillable = ['bulan', 'tanggal', 'file'];
    public $incrementing = false;
}
