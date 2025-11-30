<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view any permissions');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('view any permissions');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create permissions');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('update permissions');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete any permissions');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('delete any permissions');
    }

    public function attachAny(User $user): bool
    {
        return true;
    }

    public function attach(User $user, Permission $permission): bool
    {
        return true;
    }

    public function detachAny(User $user): bool
    {
        return true;
    }

    public function detach(User $user, Permission $permission): bool
    {
        return true;
    }
}
