<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'customer';
    protected $keyType = "string";
    protected $fillable = ['id', 'nama', 'alamat', 'telepon', 'contact_person',
                            'tempo', 'limit', 'sales_cover'];
    public $incrementing = false;
    
    public function sales() {
        return $this->belongsTo('App\Sales', 'id_sales', 'id');
    }

    public function so() {
        return $this->hasMany('App\SalesOrder', 'id_customer', 'id');
    }
}
