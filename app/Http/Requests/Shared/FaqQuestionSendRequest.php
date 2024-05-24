<?php

namespace App\Http\Requests\Shared;

use Illuminate\Foundation\Http\FormRequest;

class FaqQuestionSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'question' => 'Вопрос',
        ];
    }

    public function rules(): array
    {
        return [
            'question' => 'required|string|min:10,max:65535',
        ];
    }
}
