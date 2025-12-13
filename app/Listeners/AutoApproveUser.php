<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Carbon;

class AutoApproveUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        /** @var User $user */
        $user = $event->user;

        if (! isset($user->email) || ! is_string($user->email)) {
            return;
        }

        $domain = $user->email
            |> strtolower(...)
            |> (fn ($x) => explode('@', $x)[1]);

        if ($domain === 'ff-frankfurt-hausen.de' && $user->user_approved_at === null) {
            $user->forceFill([
                'user_approved_at' => Carbon::now(),
            ])->save();
        } else {
            // TODO: Notify administrator
        }
    }
}
