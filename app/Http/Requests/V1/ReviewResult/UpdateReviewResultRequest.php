<?php

namespace App\Http\Requests\V1\ReviewResult;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateReviewResultRequest extends FormRequest
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
            'review_id' => 'required|string|exists:reviews,_id|sometimes',
            'respondent_id' => [
                'required',
                'string',
                'not_in:' . Auth::id(),
                'sometimes',
            ],
            'answers' => 'required|array|sometimes',
            'answers.*.question_id' => 'required_with:answers|string|exists:questions,_id|distinct',
            'answers.*.answer' => 'required_with:answers.*.question_id',
        ];
    }
}
