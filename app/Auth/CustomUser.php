<?php

namespace App\Auth;

use App\Eloquent\User;

class CustomUser
{
    protected $user;

    public function setUser(User $user)
    {
    	$this->user = $user;
    }

    public function user()
    {
        return $this->user;
    }
}
