<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'area',
        'description',
        'wsm_workspace_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'target');
    }
}
