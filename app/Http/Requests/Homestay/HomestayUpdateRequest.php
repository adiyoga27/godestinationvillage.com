<?php

namespace App\Http\Requests\Homestay;

use Illuminate\Foundation\Http\FormRequest;

class HomestayUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
