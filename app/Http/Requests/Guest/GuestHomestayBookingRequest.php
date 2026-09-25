<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class GuestHomestayBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idhomestay' => 'required|exists:homestay,id',
            'customername' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'address' => 'required|string|max:1000',
            'phone' => 'required|string|max:50',
            'pax' => 'required|integer|min:1|max:500',
            'checkin_date' => 'nullable|date',
            'special_note' => 'nullable|string|max:2000',
        ];
    }
}
