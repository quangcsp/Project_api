<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }
}
