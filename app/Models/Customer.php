<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'customer';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama', 'alamat', 'telepon', 'contact_person', 'npwp',
                        'limit', 'tempo', 'id_sales', 'ktp'];
    public $incrementing = false;
    
    public function sales() {
        return $this->belongsTo('App\Models\Sales', 'id_sales', 'id');
    }

    public function so() {
        return $this->hasMany('App\Models\SalesOrder', 'id_customer', 'id');
    }
}
