<?php

namespace App\Http\Requests\V1\Review;

use Illuminate\Foundation\Http\FormRequest;

class CreateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_id' => 'required|string|exists:templates,_id',
            'title' => 'required|string',
            'users' => 'required|array|min:1',
            'users.*' => 'string|distinct|exists:users,_id',
        ];
    }
}
