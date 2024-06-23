<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'vote' => ['boolean'],
            'user_id' => ['required', 'exists:users'],
            'votable' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
