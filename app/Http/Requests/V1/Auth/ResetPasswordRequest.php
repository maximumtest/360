<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:6|max:200',
            'code' => 'string|required|exists:user_operation,code',
            'email' => 'required|string|email',
        ];
    }
}