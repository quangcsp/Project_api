<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'code',
        'avatar',
        'position',
        'role',
        'office_id',
        'tags',
        'employee_code',
        'workspaces',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'join_date'
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class)->withPivot('status', 'type', 'owner_id', 'created_at', 'days_to_read');
    }

    public function reviews()
    {
        return $this->belongsToMany(Book::class, 'reviews')->withPivot('title','content', 'star');
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function owners()
    {
        return $this->belongsToMany(Book::class, 'owners', 'user_id');
    }

    public function usersFollowing()
    {
        return $this->hasMany(UserFollow::class, 'following_id');
    }

    public function usersFollower()
    {
        return $this->hasMany(UserFollow::class, 'follower_id');
    }

    public function updateBooks()
    {
        return $this->hasMany(UpdateBook::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_send_id', 'user_receive_id');
    }

    /**
     * Check book is ownered by current user
     *
     * @param integer $bookId
     * @return boolean
     */
    public function isOwnerBook($bookId)
    {
        return $this->owners()->where('book_id', $bookId)->count() !== 0;
    }

    public function getJoinDateAttribute()
    {
        if ($this->created_at) {
            return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d/m/Y');
        }
    }
}
