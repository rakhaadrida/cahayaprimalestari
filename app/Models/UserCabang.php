<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class UserCabang extends Model
{
    protected $table = "user_cabang";
    protected $fillable = [
        'user_id',
        'cabang_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cabang() {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id');
    }
}
