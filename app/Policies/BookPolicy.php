<?php

namespace App\Policies;

use App\Eloquent\User;
use App\Eloquent\Book;

class BookPolicy extends AbstractPolicy
{
    public function read(User $user, Book $ability)
    {
        return true;
    }

    public function update(User $user, Book $ability)
    {
        $owner = $ability->owners()->where('user_id', $user->id)->count();

        if (! $owner) {
            return false;
        }

        return true;
    }

    public function delete(User $user, Book $ability)
    {
        $owner = $ability->owners()->where('user_id', $user->id)->count();

        if (! $owner) {
            return false;
        }

        return true;
    }
}
