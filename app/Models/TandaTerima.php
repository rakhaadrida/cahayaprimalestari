<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TandaTerima extends Model
{
    protected $table = 'tandaterima';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['id', 'id_so', 'tanggal', 'id_user'];

    public $incrementing = false;

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_so', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }
}
