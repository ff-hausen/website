<?php

namespace App\Policies;

use App\Models\RoleName;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }
}
