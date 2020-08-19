<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use SoftDeletes;

    protected $table = "so";
    protected $keyType = "string";
    protected $fillable = ['id', 'tgl_so', 'total', 'diskon', 'status', 'id_customer'];

    public $incrementing = false;

    public function customer() {
        return $this->belongsTo('App\Customer', 'customer');
    }
}
