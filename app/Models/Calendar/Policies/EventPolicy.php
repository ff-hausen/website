<?php

namespace App\Models\Calendar\Policies;

use App\Models\Calendar\Department;
use App\Models\Calendar\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Event $event): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return collect(Department::cases())
            ->filter(fn (Department $item) => $item->canCreate($user))
            ->count() > 0;
    }

    public function update(User $user, Event $event): bool
    {
        return $event->department->canCreate($user);
    }

    public function delete(User $user, Event $event): bool
    {
        return $event->department->canCreate($user);
    }

    public function restore(User $user, Event $event): bool
    {
        return $event->department->canCreate($user);
    }

    public function forceDelete(User $user, Event $event): bool
    {
        return $event->department->canCreate($user);
    }
}
