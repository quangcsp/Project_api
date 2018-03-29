<?php

namespace App\Traits\Eloquent;

trait GetImageTrait
{
    public function getUrlAttribute()
    {
        return route('image', ['path' => app()['glide.builder']->getUrl($this->src)]);
    }

    public function getThumbUrlAttribute()
    {
        return route('image', ['path' => app()['glide.builder']->getUrl($this->src, ['p' => 'thumbnail'])]);
    }

    public function getImageSmallAttribute()
    {
        return route('image', ['path' => app()['glide.builder']->getUrl($this->src, ['p' => 'small'])]);
    }

    public function getImageMediumAttribute()
    {
        return route('image', ['path' => app()['glide.builder']->getUrl($this->src, ['p' => 'medium'])]);
    }

    public function getImageLargeAttribute()
    {
        return route('image', ['path' => app()['glide.builder']->getUrl($this->src, ['p' => 'large'])]);
    }
}
