<?php

namespace App\Http\Requests\V1\Kudos;

use Illuminate\Foundation\Http\FormRequest;

class CreateKudosRequest extends FormRequest
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
            'text' => 'required|string',
            'kudos_category_id' => 'required|string|exists:kudos_categories,_id',
            'tags' => 'array|nullable',
            'tags.*' => 'string',
        ];
    }
}
