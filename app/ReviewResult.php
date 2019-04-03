<?php

namespace App;

use App\Traits\SetUserBeforeCreateEntryTrait;
use Jenssegers\Mongodb\Eloquent\Model;

class ReviewResult extends Model
{
    use SetUserBeforeCreateEntryTrait;

    protected $userIdField = 'interviewer_id';

    protected $fillable = [
        'review_id',
        'answers',
        'respondent_id',
        'interviewer_id',
    ];
}
