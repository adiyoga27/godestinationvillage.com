<?php

namespace App\Http\Requests\Village;

use Illuminate\Foundation\Http\FormRequest;

class VillageUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
