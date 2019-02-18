<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string|min:6|max:200',
            'code' => 'required|string|exists:user_operations,code',
        ];
    }
}