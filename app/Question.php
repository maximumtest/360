<?php

namespace App;

use App\Traits\SetUserBeforeCreateEntryTrait;
use Jenssegers\Mongodb\Eloquent\Model;

class Question extends Model
{
    use SetUserBeforeCreateEntryTrait;

    protected $fillable = [
        'question_type_id',
        'text',
        'answers',
        'author_id',
    ];

    public $timestamps = true;
}
