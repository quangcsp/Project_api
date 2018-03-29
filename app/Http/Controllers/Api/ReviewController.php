<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ReviewRepository;
use App\Contracts\Repositories\VoteRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Book\CommentRequest;

class ReviewController extends ApiController
{
    public function __construct(ReviewRepository $repository)
    {
        parent::__construct($repository);
    }

    public function delete($reviewId)
    {
        return $this->doAction(function () use ($reviewId) {
            return $this->repository->delete($reviewId);
        }, __FUNCTION__);
    }

    public function reviewDetails(VoteRepository $voteRepository, $reviewId, $userId)
    {

        return $this->doAction(function () use ($voteRepository, $reviewId, $userId) {
            $review = $this->repository->reviewDetails($reviewId, $userId);
            $currentUser = $voteRepository->checkUserVoted($userId, $reviewId);

            $userVoted = $currentUser ? $currentUser : null;
            $comments = [];
            foreach ($review->comments as $comment) {
                array_push($comments, [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => $comment->user,
                    'created_at' => $comment->time_ago
                ]);
            }

            $this->compacts['items'] = [
                'id' => $review->id,
                'title' => $review->title,
                'content' => $review->content,
                'up_vote' => $review->up_vote,
                'down_vote' => $review->down_vote,
                'current_vote' => $userVoted,
                'comments' => $comments
            ];
        }, __FUNCTION__);
    }

    public function vote(Request $request, VoteRepository $voteRepository)
    {
        return $this->doAction(function () use ($request, $voteRepository) {

            $check = $voteRepository->checkVoted($request->userId, $request->reviewId);

            if ($check) {
                if ($check->status == $request->status) {
                    $this->compacts['items'] = [
                        'messages' => config('model.review_messeges.can_not_vote')
                    ];
                } else {
                    $voteRepository->changeStatus($request->userId, $request->reviewId, $request->status);
                    if ($request->status == config('model.request_vote.up_vote')) {
                        $this->repository->increaseVote($request->reviewId);
                    } else {
                        $this->repository->decreaseVote($request->reviewId);
                    }
                    $this->compacts['items'] = [ 'messages' => config('model.review_messeges.revote_success')];
                }
            } else {
                $voteRepository->addNewVote($request->userId, $request->reviewId, $request->status);
                if ($request->status == config('model.request_vote.up_vote')) {
                    $this->repository->increaseVote($request->reviewId);
                } else {
                    $this->repository->decreaseVote($request->reviewId);
                }
                $this->compacts['items'] = [
                    'messages' => config('model.review_messeges.vote_success')
                ];
            }

        }, __FUNCTION__);
    }

    public function commentReview(CommentRequest $request)
    {
        return $this->doAction(function () use ($request) {
            $this->repository->newComment($request->all());
        }, __FUNCTION__);
    }
}
