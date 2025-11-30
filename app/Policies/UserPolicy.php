<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view any users');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('view any users');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create users');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('update users');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('delete any users');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete any users');
    }
}
