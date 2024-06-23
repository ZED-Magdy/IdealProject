<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class verifyOtpCodeRequest extends FormRequest
{
    /**
     * @return array<string,array<string>>
     */
    public function rules(): array
    {
        return [
            'otp_code' => ['required','string'],
            'phone_number' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
