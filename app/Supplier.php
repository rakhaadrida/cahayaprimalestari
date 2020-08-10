<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $table = 'supplier';

    protected $fillable = ['id', 'nama', 'alamat', 'telepon'];

    public function barang() {
        return $this->belongsToMany('App\Barang');
    }
}
