<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_send_id',
        'user_receive_id',
        'target_id',
        'viewed',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'target_id');
    }

    public function userSend()
    {
        return $this->belongsTo(User::class, 'user_send_id');
    }

    public function userReceive()
    {
        return $this->belongsTo(User::class, 'user_receive_id');
    }
}
