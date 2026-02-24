<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        if (User::count() === 0) {
            $user->is_global_admin = true;
        }
    }
}
