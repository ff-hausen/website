<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UpcomingScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc');
    }
}
