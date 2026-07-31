<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class PackageCreateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
