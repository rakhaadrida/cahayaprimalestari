<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subjenis extends Model
{
    use SoftDeletes;
    protected $table = 'subjenis';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama', 'id_kategori', 'limit'];
    public $incrementing = false;
    
    public function jenis() {
        return $this->belongsTo('App\Models\JenisBarang', 'id_kategori', 'id');
    }
}
