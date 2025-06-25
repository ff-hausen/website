<?php

namespace App\Policies;

use App\Models\AusflugParticipant;
use App\Models\RoleName;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AusflugParticipantPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasAnyRole([RoleName::Administrator, RoleName::Vereinsvorstand])) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, AusflugParticipant $ausflugParticipant): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AusflugParticipant $ausflugParticipant): bool
    {
        return false;
    }

    public function delete(User $user, AusflugParticipant $ausflugParticipant): bool
    {
        return false;
    }
}
