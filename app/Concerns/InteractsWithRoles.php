<?php

namespace App\Concerns;

use App\Models\RoleName;

trait InteractsWithRoles
{
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
