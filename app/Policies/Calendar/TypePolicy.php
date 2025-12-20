<?php

namespace App\Policies\Calendar;

use App\Models\Calendar\Type;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {}

    public function view(User $user, Type $type): bool {}

    public function create(User $user): bool {}

    public function update(User $user, Type $type): bool {}

    public function delete(User $user, Type $type): bool {}
}
