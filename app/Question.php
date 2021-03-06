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
        'template_ids',
    ];

    public $timestamps = true;

    public function templates()
    {
        return $this->belongsToMany(Template::class);
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function getHasAnswersAttribute()
    {
        return in_array($this->questionType->name, [
            QuestionType::TYPE_SELECT,
            QuestionType::TYPE_RADIO,
            QuestionType::TYPE_CHECKBOX,
        ]);
    }
}
