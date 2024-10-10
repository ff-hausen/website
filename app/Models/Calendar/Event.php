<?php

namespace App\Models\Calendar;

use App\Models\Scopes\UpcomingScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy(UpcomingScope::class)]
class Event extends Model
{
    use SoftDeletes;

    protected $table = 'calendar_events';

    protected $with = 'type';

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'all_day',
        'department',
        'created_by',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function responsible(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'calendar_responsibility');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'department' => Department::class,
            'all_day' => 'bool',
        ];
    }
}
