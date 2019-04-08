<?php

namespace App\Http\Requests\V1\KudosCategory;

use Illuminate\Foundation\Http\FormRequest;

class CreateKudosCategoryRequest extends FormRequest
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
            'name' => 'required|string|unique:kudos_categories',
        ];
    }
}
