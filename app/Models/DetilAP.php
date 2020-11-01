<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class DetilAP extends Model
{
    use CompositePrimaryKey;
    
    protected $table = 'detilap';
    protected $primaryKey = ['id_ap', 'id_bayar'];
    protected $keyType = 'string';
    protected $fillable = ['id_ap', 'id_bayar', 'tgl_bayar', 'transfer'];

    public $incrementing = false;

    public function ap() {
        return $this->belongsTo('App\Models\AccPayable', 'id_ap', 'id');
    }
}
