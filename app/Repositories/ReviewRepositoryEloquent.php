<?php

namespace App\Repositories;

use App\Contracts\Repositories\ReviewRepository;
use App\Eloquent\Review;

class ReviewRepositoryEloquent extends AbstractRepositoryEloquent implements ReviewRepository
{
    public function model()
    {
        return new Review;
    }

    public function delete($reviewId)
    {
        return $this->model()->destroy($reviewId);
    }

    public function reviewDetails($reviewId, $userId)
    {
        return $this->model()->findOrFail($reviewId);
    }

    public function vote($userId, $reviewId, $status)
    {

        $check = $this->model()->where([
            ['user_id', '=', $userId],
            ['review_id', '=', $reviewId],
        ])->first();

        return $check;
    }

    public function increaseVote($reviewId)
    {
        return $this->model()->where('id', $reviewId)->increment('up_vote', 1);
    }

    public function decreaseVote($reviewId)
    {
        return $this->model()->where('id', $reviewId)->increment('down_vote', 1);
    }

    public function newComment($data)
    {
        return $this->model()->findOrFail($data['item']['reviewId'])->comments()->create(
            [
                'user_id' => $data['item']['userId'],
                'review_id' => $data['item']['reviewId'],
                'content' => $data['item']['content'],
            ]
        );
    }
}
