<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * @return array<string,array<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string'],
            'email' => ['required','email','unique:users'],
            'phone_number' => ['required','unique:users'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
