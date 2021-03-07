<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositePrimaryKey;

class Keuangan extends Model
{
    use CompositePrimaryKey;
    
    protected $table = 'keuangan';
    protected $primaryKey = ['tahun', 'bulan'];
    protected $keyType = 'string';
    protected $fillable = ['tahun', 'bulan', 'pendapatan', 'beban_gaji', 'beban_jual', 'beban_lain', 'petty_cash'];
    
    public $incrementing = false;
}
