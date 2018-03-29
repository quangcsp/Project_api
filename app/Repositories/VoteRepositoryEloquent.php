<?php

namespace App\Repositories;

use App\Contracts\Repositories\VoteRepository;
use App\Eloquent\Vote;

class VoteRepositoryEloquent extends AbstractRepositoryEloquent implements VoteRepository
{
    public function model()
    {
        return new Vote;
    }


    public function checkVoted($userId, $reviewId)
    {

        $check = $this->model()->where([
            ['user_id', '=', $userId],
            ['review_id', '=', $reviewId],
        ])->first();

        return $check;
    }

    public function addNewVote($userId, $reviewId, $status)
    {
        $this->model()->insert([
            [
                'user_id' => $userId,
                'review_id' => $reviewId,
                'status' => $status
            ],
        ]);
    }

    public function changeStatus($userId, $reviewId, $status)
    {
        $this->model()->where([
            ['user_id', '=', $userId],
            ['review_id', '=', $reviewId],
        ])->update(['status' => $status]);
    }

    public function checkUserVoted($userId, $reviewId){
         $checkVoted = $this->model()->where([
            ['user_id', '=', $userId],
            ['review_id', '=', $reviewId],
        ])->first();

        if ($checkVoted) {
            return $checkVoted->status;
        }
        return false;
    }
}
