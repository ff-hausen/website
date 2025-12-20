<?php

namespace App\Models\Calendar;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'calendar_events';

    protected $fillable = [
        'title',
        'description',
        'starts_at',
        'ends_at',
        'is_all_day',
        'department',
    ];

    public function responsible(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'calendar_responsibility');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_all_day' => 'boolean',
            'department' => Department::class,
        ];
    }
}
