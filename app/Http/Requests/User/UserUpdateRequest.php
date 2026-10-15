<?php

namespace App\Http\Requests\User;

use App\Rules\UniqueUserEmail;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        // dipakai oleh user-admin dan user-member
        $id = $this->route('user_admin') ?? $this->route('user_member');

        return [
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', new UniqueUserEmail($id)],
            'password' => 'nullable|string|min:6|confirmed',
            'phone'    => 'required|string|max:50',
        ];
    }
}
