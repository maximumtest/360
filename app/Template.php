<?php

namespace App;

use App\Traits\SetUserBeforeCreateEntryTrait;
use Jenssegers\Mongodb\Eloquent\Model;

class Template extends Model
{
    use SetUserBeforeCreateEntryTrait;

    protected $fillable = [
        'name',
        'author_id',
        'question_ids',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }
    
    public function getQuestionIdsAttribute()
    {
        return $this->questions->pluck('question_id');
    }
}
