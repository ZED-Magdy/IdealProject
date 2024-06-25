<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteCommentRequest extends FormRequest
{
    /**
     * @return array[]
     * @psalm-return array{comment_id: array{0: string, 1: string, 2: string}}
     */
    public function rules(): array
    {
        return [
            'comment_id' => ['required', 'integer', 'exists:comments,id'],
        ];
    }

    /**
     * @return bool
     * @psalm-return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
