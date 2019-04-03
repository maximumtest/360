<?php

namespace App\Http\Requests\V1\Template;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|sometimes',
            'questions' => 'required|array|min:1|sometimes',
            'questions.*' => 'string|distinct|exists:questions,_id',
        ];
    }
}
