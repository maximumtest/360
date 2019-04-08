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
        return (
            ($user->getId() == $reviewResult->interviewer_id)) ||
            ($user->getId() == $reviewResult->respondent_id)
            ;
    }

    /**
     * Determine whether the user can create review results.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $reviewId)
    {
        return $this->isAccess($user, Review::findOrFail($reviewId));
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
        return $this->isAccess($user, $reviewResult->review()->first());
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
    
    private function isAccess(User $user, Review $review)
    {
        $questionIds = Review::findOrFail(request('review_id'))->template()->questions()->pluck('id');
    
        $requestQuestions = array_filter(request('answers'), function ($value, $key) {
            return $key == 'question_id';
        }, ARRAY_FILTER_USE_BOTH);
    
        $reviewUsers = $review->users()->pluck('id');
        return (
            ($user->getId() != request('respondent_id'))) &&
            (in_array(request('respondent_id'), $reviewUsers->toArray())) &&
            (in_array($requestQuestions, $questionIds)
            );
    }
}
