<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FakturItem extends Model
{
    use SoftDeletes;

    protected $table = "faktur_item";
    protected $fillable = [
        'id_faktur', 
        'id_barang',
        'qty',
        'harga',
        'jumlah'
    ];

    public function faktur() {
        return $this->belongsTo(Faktur::class, 'id_so', 'id');
    }

    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang', 'id')->withTrashed();
    }
}
