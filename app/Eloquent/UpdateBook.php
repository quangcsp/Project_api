<?php

namespace App\Eloquent;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;

class UpdateBook extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'title',
        'description',
        'author',
        'publish_date',
        'category_id',
        'office_id',
    ];

    protected $hidden = ['category_id', 'office_id', 'user_id', 'book_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function currentBookInfo()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function userRequest()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updateMedia()
    {
        return $this->hasMany(UpdateMedia::class);
    }

    public function image()
    {
        return $this->hasMany(UpdateMedia::class)->where('type', config('model.media.type.avatar_book'));
    }
}
