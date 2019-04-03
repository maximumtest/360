<?php

namespace App\Http\Requests\V1\Review;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_id' => 'required|string|exists:templates,_id|sometimes',
            'title' => 'required|string|sometimes',
            'users' => 'required|array|min:1|sometimes',
            'users.*' => 'string|distinct|exists:users,_id',
            'review_status_id' => 'string|exists:review_statuses,_id|nullable',
        ];
    }
}
