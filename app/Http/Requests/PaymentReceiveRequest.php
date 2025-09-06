<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentReceiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paid_at' => 'required|date',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'transaction_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}
