<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class UserFollow extends Model
{
    protected $table = 'user_follow';

    protected $fillable = [
        'following_id',
        'follower_id',
    ];

    public function userFollower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function userFollowing()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
