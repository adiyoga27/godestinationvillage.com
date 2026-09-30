<?php

namespace App\Http\Requests\Homestay;

use Illuminate\Foundation\Http\FormRequest;

class HomestayUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'default_img' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ];
    }

    public function messages(): array
    {
        return [
            'default_img.image' => 'File harus berupa gambar.',
            'default_img.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'default_img.max' => 'Ukuran gambar maks. 1MB.',
        ];
    }
}
