<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class NeedAppDetil extends Model
{
    use CompositePrimaryKey;

    protected $table = "need_appdetil";
    protected $primaryKey = ['id_app', 'id_barang'];
    protected $keyType = "string";
    protected $fillable = ['id_app', 'id_barang', 'qty', 'harga', 'diskon'];
    public $incrementing = false;

    public function barang() {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }

    public function need_app() {
        return $this->belongsTo('App\Models\NeedApproval', 'id_app', 'id');
    }
}
