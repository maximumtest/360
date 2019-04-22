<?php

namespace App\Policies;

use App\Review;
use App\User;
use App\ReviewResult;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewResultPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability, $reviewResult)
    {
        if ($reviewResult) {
            if ($user->isManager($reviewResult->review()->first())) {
                return true;
            }
        }

        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the review result.
     *
     * @param  \App\User  $user
     * @param  \App\ReviewResult  $reviewResult
     * @return mixed
     */
    public function view(User $user, ReviewResult $reviewResult)
    {
        return $user->getId() == $reviewResult->interviewer_id || $user->getId() == $reviewResult->respondent_id;
    }

    /**
     * Determine whether the user can create review results.
     *
     * @param  \App\User  $user
     * @param ReviewResult $reviewResult
     * @return mixed
     */
    public function create(User $user, ReviewResult $reviewResult)
    {
        return $this->isValidReviewResult(Review::findOrFail($reviewResult->review_id));
    }

    /**
     * Determine whether the user can update the review result.
     *
     * @param  \App\User  $user
     * @param  \App\ReviewResult  $reviewResult
     * @return mixed
     */
    public function update(User $user, ReviewResult $reviewResult)
    {
        return $this->isValidReviewResult($reviewResult->review);
    }

    /**
     * Determine whether the user can delete the review result.
     *
     * @param  \App\User  $user
     * @param  \App\ReviewResult  $reviewResult
     * @return mixed
     */
    public function delete(User $user, ReviewResult $reviewResult)
    {
        return $user->getId() == $reviewResult->interviewer_id;
    }

    private function isValidReviewResult(Review $review): bool
    {
        $questionIds = $review->getQuestionsAttribute()->pluck('_id')->toArray();

        $requestQuestions = array_map(function ($item) {
            return  $item['question_id'];
        }, request('answers'));

        $reviewUsers = $review->users()->pluck('_id')->toArray();

        return in_array(request('respondent_id'), $reviewUsers)
            && in_array(request('interviewer_id'), $reviewUsers)
            && empty(array_diff($requestQuestions, $questionIds));
    }
}
