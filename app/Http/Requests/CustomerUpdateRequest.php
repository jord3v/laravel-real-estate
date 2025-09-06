<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:lessor,lessee,guarantor,prospect,agent,supplier,administrator',
            'email' => 'nullable|email|unique:customers,email,' . $customerId,
            'phone' => 'nullable|string',
            'document_type' => 'required|string|in:pf,pj',
            'cpf' => 'required_if:document_type,pf|nullable|string|unique:customers,cpf,' . $customerId,
            'rg' => 'required_if:document_type,pf|nullable|string',
            'marital_status' => 'required_if:document_type,pf|nullable|string',
            'nationality' => 'required_if:document_type,pf|nullable|string',
            'profession' => 'required_if:document_type,pf|nullable|string',
            'spouse_name' => 'nullable|string',
            'spouse_rg' => 'nullable|string',
            'spouse_cpf' => 'nullable|string',
            'spouse_profession' => 'nullable|string',
            'company_name' => 'required_if:document_type,pj|nullable|string|max:255',
            'cnpj' => 'required_if:document_type,pj|nullable|string|unique:customers,cnpj,' . $customerId,
        ];
    }
}
