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

    /**
     * @param  RoleName[]  $roleNames
     */
    public function hasAnyRole(array $roleNames): bool
    {
        foreach ($roleNames as $roleName) {
            if ($this->hasRole($roleName)) {
                return true;
            }
        }

        return false;
    }
}
