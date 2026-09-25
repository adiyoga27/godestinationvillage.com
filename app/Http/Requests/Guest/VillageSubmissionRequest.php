<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class VillageSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'village_name' => 'required|string|max:191',
            'contact_name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:2000',
            'regency' => 'nullable|string|max:191',
            'description' => 'nullable|string|max:5000',
            'tourism_potential' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'village_name.required' => 'Nama desa wajib diisi.',
            'contact_name.required' => 'Nama kontak wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'address.required' => 'Alamat desa wajib diisi.',
        ];
    }
}
