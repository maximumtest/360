<?php

namespace App\Http\Requests\V1\Template;

use Illuminate\Foundation\Http\FormRequest;

class CreateTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*' => 'string|distinct|exists:questions,_id',
        ];
    }
}
