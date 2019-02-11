<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'email' => 'sometimes|required|string|email|unique:user|max:255',
            'password' => 'sometimes|required|string|min:6|max:255'
        ];
    }
}