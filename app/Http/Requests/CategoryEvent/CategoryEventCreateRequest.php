<?php

namespace App\Http\Requests\CategoryEvent;

use Illuminate\Foundation\Http\FormRequest;

class CategoryEventCreateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
