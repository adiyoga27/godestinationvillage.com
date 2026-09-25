<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => 'required|array',
            'answers.*' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'Mohon isi seluruh pernyataan asesmen.',
            'answers.*.required' => 'Masih ada pernyataan yang belum dijawab.',
        ];
    }
}
