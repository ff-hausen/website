<?php

namespace App\Policies;

use App\Models\ContactFormTopics;
use App\Models\RoleName;
use App\Models\User;

class ContactFormTopicsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContactFormTopics $contactFormTopics): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContactFormTopics $contactFormTopics): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContactFormTopics $contactFormTopics): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContactFormTopics $contactFormTopics): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContactFormTopics $contactFormTopics): bool
    {
        return $user->hasRole(RoleName::Administrator);
    }
}
