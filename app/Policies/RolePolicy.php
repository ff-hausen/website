<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('roles:view-any');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('roles:view-any');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('roles:create');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('roles:update');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('roles:delete-any');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('roles:delete-any');
    }
}
