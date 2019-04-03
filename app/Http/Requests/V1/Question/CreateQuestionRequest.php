<?php

namespace App\Http\Requests\V1\Question;

use App\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\QuestionType;

class CreateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question_type_id' => 'required|string|exists:question_types,_id',
            'text' => 'required|string',
            'answers' => [
                'array',
                'min:2',
                Rule::requiredIf(function () {
                    return QuestionType::isRequiredAnswers($this->input('question_type_id'));
                }),
            ],
            'answers.*' => 'distinct',
        ];
    }
}
