<?php

namespace App\Eloquent;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'author',
        'publish_date',
        'total_page',
        'avg_star',
        'code',
        'count_view',
        'status',
        'category_id',
        'office_id',
    ];

    protected $hidden = ['category_id', 'office_id'];

    protected $appends = ['overview'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('status', 'type', 'created_at', 'updated_at' , 'owner_id', 'days_to_read');
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'owners')->withPivot('status');
    }

    public function updateBooks()
    {
        return $this->hasMany(UpdateBook::class);
    }

    public function usersReading()
    {
        return $this->users()->wherePivot('status', config('model.book_user.status.reading'));
    }

    public function usersWaiting()
    {
        return $this->users()->wherePivot('status', config('model.book_user.status.waiting'));
    }

    public function usersReturning()
    {
        return $this->users()->wherePivot('status', config('model.book_user.status.returning'));
    }

    public function usersReturned()
    {
        return $this->users()->wherePivot('status', config('model.book_user.status.returned'));
    }

    public function reviews()
    {
        return $this->belongsToMany(User::class, 'reviews')->withPivot('content', 'star');
    }

    public function reviewsDetail()
    {
        return $this->hasMany(Review::class, 'book_id')->with('user');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'target');
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'target')->where('type', config('model.media.type.avatar_book'));
    }

    public function scopeGetData($query, $field, $filters = [], $orderBy = 'DESC', $officeId = '')
    {
        return $query->where(function ($query) use ($field, $filters, $officeId) {
            if ($field == config('model.filter_books.view.field')) {
                $query->where(config('model.filter_books.view.field'), '>', 0);
            }

            if ($field == config('model.filter_books.rating.field')) {
                $query->where(config('model.filter_books.rating.field'), '>', 0);
            }

            if ($filters) {
                foreach ($filters as $value) {
                    foreach ($value as $filter => $filterIds) {
                        if (in_array($filter, config('model.filter_type'))) {
                            $query->whereIn($filter . '_id', $filterIds);
                        }
                    }
                }
            }
        })->orderBy($field, $orderBy);
    }

    public function getAvgStarAttribute($value)
    {
        return round($value, config('settings.round_average_star'));
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($book) {
            Event::fire('book.deleted', $book);
        });
    }

    public function getOverviewAttribute()
    {
        return str_limit($this->description, config('paginate.overview_limit'));
    }

    public function scopeGetLatestBooks($query, $dataSelect, $with)
    {
       return $query->select($dataSelect)->with($with)->orderBy('created_at','desc');
    }

    public function scopeGetBookByOffice($query, $officeId)
    {
        if ($officeId) {
            return $query->where('office_id', $officeId);
        }
    }
}
