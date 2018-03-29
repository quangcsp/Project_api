<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'title',
        'content',
        'star' .
        'up_vote',
        'down_vote'
    ];

    protected $hidden = ['user_id', 'book_id', 'owner_id', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
