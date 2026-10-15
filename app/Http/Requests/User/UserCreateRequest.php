<?php

namespace App\Http\Requests\User;

use App\Rules\UniqueUserEmail;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', new UniqueUserEmail],
            'password' => 'required|string|min:6|confirmed',
            'phone'    => 'required|string|max:50',
        ];
    }
}
