<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => 'nullable|exists:properties,id',
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'attended_at' => 'nullable|date',
        ];
    }
}
