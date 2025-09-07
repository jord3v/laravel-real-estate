<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phones' => 'nullable|array',
            'phones.*' => 'nullable|string|max:20',
            'phones_whatsapp' => 'nullable|array',
            'phones_whatsapp.*' => 'nullable|boolean',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'address.cep' => 'nullable|string|max:20',
            'address.street' => 'nullable|string|max:255',
            'address.number' => 'nullable|string|max:20',
            'address.neighborhood' => 'nullable|string|max:255',
            'address.city' => 'nullable|string|max:255',
            'address.state' => 'nullable|string|max:255',
            'business_hours' => 'nullable|array',
            'business_hours.*.weekday' => 'nullable|string|max:10',
            'business_hours.*.start' => 'nullable|string|max:5',
            'business_hours.*.end' => 'nullable|string|max:5',
            'theme' => 'nullable|string|max:255',
            // outros campos do tenant...
        ];
    }
}
