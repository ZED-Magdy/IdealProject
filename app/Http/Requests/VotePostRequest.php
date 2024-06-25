<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VotePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return array<string, ValidationRule|array<mixed>|string|int>
     */
    public function rules(): array
    {
        return [
            'post_id' => ['required', 'integer', 'exists:posts,id'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }
}
