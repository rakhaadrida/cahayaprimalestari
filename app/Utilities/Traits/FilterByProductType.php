<?php

namespace App\Utilities\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FilterByProductType
{
    protected static function bootFilterByProductType(): void
    {
        static::addGlobalScope('product_type', function (Builder $builder) {
            if (Auth::user()->roles != 'CIANJUR') {
                $builder->where($builder->getModel()->getTable() . '.tipe', 'GENERAL');
            }
        });
    }
}
