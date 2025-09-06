<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lessor_id' => 'required|exists:customers,id',
            'lessee_id' => 'required|exists:customers,id',
            'property_id' => 'required|exists:properties,id',
            'contract_type' => 'nullable|string',
            'term_months' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'rent_amount' => 'required|numeric|min:0',
            'due_day' => 'required|integer|min:1|max:31',
            'payment_place' => 'nullable|string',
            'readjustment_index' => 'required|string',
            'alternative_indexes' => 'nullable|array',
            'late_payment_fine_percent' => 'nullable|numeric|min:0',
            'late_payment_fine_limit' => 'nullable|numeric|min:0',
            'late_payment_interest' => 'nullable|numeric|min:0',
            'monetary_correction' => 'nullable|boolean',
            'additional_charges' => 'nullable|array',
            'use_destination' => 'required|string',
            'maintenance_obligations' => 'required|string',
            'benfeitorias' => 'nullable|boolean',
            'guarantee_type' => 'required|string|in:fianca,caucao_dinheiro,seguro_fianca,caucao_imobiliaria',
            'guarantor_id' => 'required_if:guarantee_type,fianca|nullable|exists:customers,id',
            'attorney_fees_percent' => 'required|numeric|min:0',
            'elected_forum' => 'required|string',
            'via_count' => 'required|integer|min:1',
        ];
    }
}
