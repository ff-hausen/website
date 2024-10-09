<?php

namespace App\Models\Calendar;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    protected $table = 'calendar_types';

    protected $fillable = [
        'name',
        'department',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
