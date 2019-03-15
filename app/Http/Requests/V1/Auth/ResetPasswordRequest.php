<?php

namespace App\Http\Requests\V1\Auth;

use App\Http\Requests\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    use FailedValidationTrait;
    
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:6|max:200',
            'code' => 'string|required|exists:user_codes,code',
            'email' => 'required|string|email|exists:users,email',
        ];
    }
}