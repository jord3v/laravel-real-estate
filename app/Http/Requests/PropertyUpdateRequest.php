<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Adicione as regras de validação conforme os campos do Property
            'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string',
            'owner_id' => 'required|exists:customers,id',
            'purpose' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'compositions' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'characteristics' => 'nullable|string',
            'business_options' => 'nullable|string',
            'description' => 'nullable|string',
            'publication' => 'nullable|string',
            // Adicione outros campos conforme necessário
        ];
    }
}
