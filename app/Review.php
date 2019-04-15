<?php

namespace App;

use App\Traits\SetUserBeforeCreateEntryTrait;
use Jenssegers\Mongodb\Eloquent\Model;

class Review extends Model
{
    use SetUserBeforeCreateEntryTrait;

    protected $userIdField = 'manager_id';

    protected $fillable = [
        'manager_id',
        'template_id',
        'title',
        'review_status_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
    
    public function reviewResults()
    {
        return $this->hasMany(ReviewResult::class);
    }
    
    public function getQuestionsAttribute()
    {
        $template = $this->template()->has('questions')->with('questions')->get();
        
        return collect($template->pluck('questions')->collapse()->unique());
    }
}
