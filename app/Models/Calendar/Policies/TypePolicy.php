<?php

namespace App\Models\Calendar\Policies;

use App\Models\Calendar\Type;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Type $type): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Type $type): bool
    {
        return true;
    }

    public function delete(User $user, Type $type): bool
    {
        return true;
    }

    public function restore(User $user, Type $type): bool
    {
        return true;
    }

    public function forceDelete(User $user, Type $type): bool
    {
        return true;
    }
}
