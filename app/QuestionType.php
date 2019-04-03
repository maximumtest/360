<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class QuestionType extends Model
{
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_SELECT = 'select';
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';

    protected $fillable = [
        'name',
    ];

    public static function isRequiredAnswers($questionTypeId)
    {
        return static::whereIn('name', [
            static::TYPE_RADIO,
            static::TYPE_CHECKBOX,
            static::TYPE_SELECT,
        ])->where('_id', $questionTypeId)->count() > 0;
    }
}
