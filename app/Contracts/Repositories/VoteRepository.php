<?php

namespace App\Contracts\Repositories;

interface VoteRepository extends AbstractRepository
{
    public function checkVoted($userId, $reviewId);
    public function addNewVote($userId, $reviewId, $status);
    public function changeStatus($userId, $reviewId, $status);
}
