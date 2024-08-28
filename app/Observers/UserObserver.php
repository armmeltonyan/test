<?php

namespace App\Observers;

use App\Events\UserRegistered;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        event(new UserRegistered($user));
    }
}
