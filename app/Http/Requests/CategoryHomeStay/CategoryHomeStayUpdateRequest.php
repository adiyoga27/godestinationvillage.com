<?php

namespace App\Http\Requests\CategoryHomeStay;

use Illuminate\Foundation\Http\FormRequest;

class CategoryHomeStayUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
