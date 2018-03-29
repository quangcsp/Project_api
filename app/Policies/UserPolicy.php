<?php

namespace App\Policies;

use App\Eloquent\User;

class UserPolicy extends AbstractPolicy
{
    public function read(User $user, User $ability)
    {
        return true;
    }
}
