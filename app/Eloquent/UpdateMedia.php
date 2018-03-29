<?php

namespace App\Eloquent;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;

class UpdateMedia extends Model
{
    protected $table = 'update_medias';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'update_book_id',
        'media_id',
        'name',
        'path',
        'size',
        'type',
        'thumb_path',
    ];

    protected $hidden = ['thumb_path', 'created_at', 'updated_at'];

    protected $appends = ['mobile', 'web'];

    private function responseMediaStorage($size = null)
    {
        if (is_array($size)) {
            $mediaPath = [];

            foreach ($size as $item => $value) {
                $mediaPath[$item] = route('image',
                    ['path' => app()['glide.builder']->getUrl($this->path, ['p' => ($value) ?: null])]
                );
            }

            return $mediaPath;
        }

        return route('image',
            ['path' => app()['glide.builder']->getUrl($this->path, ['p' => ($size) ?: null])]
        );
    }

    public function getMobileAttribute()
    {
        if ($this->path) {
            return $this->responseMediaStorage([
                'thumbnail_path' => 'thumbnail',
                'small_path' => 'small',
                'medium_path' => 'medium',
                'large_path' => 'large',
            ]);
        }

        return $this->thumb_path;
    }

    public function getWebAttribute()
    {
        if ($this->path) {
            return $this->responseMediaStorage([
                'thumbnail_path' => 'thumbnail_web',
                'small_path' => 'small_web',
                'medium_path' => 'medium_web',
                'large_path' => 'large_web',
            ]);
        }

        return $this->thumb_path;
    }
}
