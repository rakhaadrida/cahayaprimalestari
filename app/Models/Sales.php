<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;
    protected $table = "sales";
    protected $keyType = 'string';
    protected $fillable = ['id', 'nama', 'id_cabang'];
    public $incrementing = false;

    public function cabang() {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id');
    }
    public function customer() {
        return $this->hasMany('App\Models\Customer', 'id_sales', 'id');
    }
}
