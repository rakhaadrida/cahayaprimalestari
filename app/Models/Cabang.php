<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cabang extends Model
{
    use SoftDeletes;

    protected $table = "cabang";
    protected $fillable = [
        'nama'
    ];
}
