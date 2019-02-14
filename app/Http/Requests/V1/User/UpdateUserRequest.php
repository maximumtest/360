<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'email' => 'sometimes|required|string|email|max:255|unique:user,email' . Auth::user()->getId(),
            'password' => 'sometimes|required|string|min:6|max:200',
        ];
    }
}