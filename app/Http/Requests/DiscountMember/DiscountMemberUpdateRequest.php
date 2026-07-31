<?php

namespace App\Http\Requests\DiscountMember;

use Illuminate\Foundation\Http\FormRequest;

class DiscountMemberUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
