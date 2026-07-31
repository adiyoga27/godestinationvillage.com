<?php

namespace App\Http\Requests\Village;

use Illuminate\Foundation\Http\FormRequest;

class VillageCreateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
