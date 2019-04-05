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
    
    public function user()
    {
        return $this->hasOne(User::class, 'interviewer_id');
    }
}
