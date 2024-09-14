<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $visible = [
        'name',
    ];

    protected function casts(): array
    {
        return [
            'name' => RoleName::class,
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public static function byName(RoleName $roleName): static
    {
        return static::whereName($roleName->value)->first();
    }
}
