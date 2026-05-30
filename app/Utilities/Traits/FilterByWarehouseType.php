<?php

namespace App\Utilities\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FilterByWarehouseType
{
    protected static function bootFilterByWarehouseType(): void
    {
        static::addGlobalScope('warehouse_type', function (Builder $builder) {
            if (Auth::user()->roles != 'CIANJUR') {
                $builder->where($builder->getModel()->getTable() . '.tipe', '!=', 'TOKO');
            }
        });
    }
}
