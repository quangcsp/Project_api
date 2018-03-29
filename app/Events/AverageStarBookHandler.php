<?php

namespace App\Events;

use App\Eloquent\Book;

class AverageStarBookHandler
{
    protected $data;

    public function handle($data)
    {
        $this->data = $data;

        $currentCountReview = $this->data['book']->reviews->count();
        $newAverageStar = ($this->data['book']->avg_star * ($currentCountReview - 1) + $this->data['star'])
            / $currentCountReview;
        $this->data['book']->update(['avg_star' => $newAverageStar]);
    }
}
