<?php

namespace App\Concerns;

use App\Models\RoleName;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait InteractsWithRoles
{
    public function roleNames(): Attribute
    {
        return Attribute::get(fn () => $this->roles->pluck('name'));
    }

    public function hasRole(RoleName $roleName): bool
    {
        return $this->roles->where('name', $roleName)->isNotEmpty();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleName::Administrator);
    }
}
