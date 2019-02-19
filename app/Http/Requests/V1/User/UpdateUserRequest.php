<?php

namespace App\Http\Requests\V1\User;

use App\Http\Requests\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    use FailedValidationTrait;
    
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'email' => 'sometimes|required|string|email|max:255|unique:users' . Auth::user()->getId(),
            'password' => 'sometimes|required|string|min:6|max:200',
            'role' => 'sometimes|required|string|exists:roles,name',
            'department' => 'sometimes|required|string|exists:departments,name',
        ];
    }
}