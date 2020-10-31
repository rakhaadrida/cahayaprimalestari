<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilAR extends Model
{
    use CompositePrimaryKey;

    protected $table = 'detilar';
    protected $primaryKey = ['id_ar', 'id_cicil'];
    protected $keyType = 'string';
    protected $fillable = ['id_ar', 'id_cicil', 'tgl_bayar', 'cicil'];

    public $incrementing = false;

    public function ar() {
        return $this->belongsTo('App\Models\AccReceivable', 'id_ar', 'id');
    }
}
