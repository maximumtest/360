<?php

namespace App\Policies;

use App\Review;
use App\User;
use App\ReviewResult;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewResultPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $reviewResultId)
    {
        if ($reviewResultId) {
            $reviewResult = ReviewResult::findOrFail($reviewResultId);
            
            if ($user->isManager($reviewResult->review())) {
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
    public function create(User $user)
    {
        $questionIds = Review::findOrFail(request('review_id'))->template()->questions()->pluck('id');
        
        return (
            ($user->getId() != request('respondent_id'))) ||
            (in_array(request('answers.question_id'), $questionIds)
            );
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
        $questionIds = Review::findOrFail(request('review_id'))->template()->questions()->pluck('id');
    
        return (
            ($user->getId() != request('respondent_id'))) ||
            (in_array($reviewResult->answers->pluck('question_id'), $questionIds)
            );
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
}
