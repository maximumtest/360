<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class ReviewResult extends Model
{
    protected $fillable = [
        'review_id',
        'answers',
        'respondent_id',
        'interviewer_id',
    ];
    
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
    
    public function respondent()
    {
        return $this->belongsTo(ReviewResult::class, 'respondent_id');
    }
    
    public function interviewer()
    {
        return $this->belongsTo(ReviewResult::class,'interviewer_id');
    }
}
