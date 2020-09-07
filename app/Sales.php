<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;
    protected $table = "sales";
    protected $keyType = 'string';
    protected $fillable = ['id', 'nama'];
    public $incrementing = false;

    public function customer() {
        return $this->hasOne('App\Customer', 'id_sales', 'id');
    }
}
