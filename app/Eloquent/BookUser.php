<?php

namespace App\Eloquent;

class BookUser extends AbstractEloquent
{
    protected $table = 'book_user';

    protected $fillable = [
        'type',
        'status',
        'user_id',
        'book_id'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
