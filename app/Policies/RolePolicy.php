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
        return $user->hasPermissionTo('view any roles');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('view any roles');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create roles');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('update roles');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('delete any roles');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete any roles');
    }
}
