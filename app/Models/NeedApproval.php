<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NeedApproval extends Model
{
    protected $table = "need_approval";
    protected $keyType = "string";
    protected $fillable = ['id', 'tanggal', 'status', 'keterangan', 'id_so'];

    public $incrementing = false;

    public function so() {
        return $this->belongsTo('App\Models\SalesOrder', 'id_so');
    }

    public function need_appdetil() {
        return $this->hasMany('App\Models\NeedAppDetil', 'id_app', 'id');
    }
}
