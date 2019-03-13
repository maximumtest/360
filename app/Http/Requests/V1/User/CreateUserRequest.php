<?php

namespace App\Http\Requests\V1\User;

use App\Http\Requests\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    use FailedValidationTrait;
    
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'string|min:6|max:255',
            'role_id' => 'required|string|exists:roles,_id',
            'department_id' => 'string|exists:departments,_id',
        ];
    }
}