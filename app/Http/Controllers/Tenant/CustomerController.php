<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $customers = Customer::latest()->paginate(15);
        return view('tenant.dashboard.customers.index', compact('customers'));
    }

    public function create(): \Illuminate\View\View
    {
        return view('tenant.dashboard.customers.create');
    }

    public function store(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:lessor,lessee,guarantor,prospect,agent,supplier,administrator',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string',
            'document_type' => 'required|string|in:pf,pj',
            'cpf' => 'required_if:document_type,pf|nullable|string|unique:customers,cpf',
            'rg' => 'required_if:document_type,pf|nullable|string',
            'marital_status' => 'required_if:document_type,pf|nullable|string',
            'nationality' => 'required_if:document_type,pf|nullable|string',
            'profession' => 'required_if:document_type,pf|nullable|string',
            'spouse_name' => 'nullable|string',
            'spouse_rg' => 'nullable|string',
            'spouse_cpf' => 'nullable|string',
            'spouse_profession' => 'nullable|string',
            'company_name' => 'required_if:document_type,pj|nullable|string|max:255',
            'cnpj' => 'required_if:document_type,pj|nullable|string|unique:customers,cnpj',
        ]);
        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function edit(Customer $customer): \Illuminate\View\View
    {
        return view('tenant.dashboard.customers.edit', compact('customer'));
    }

    public function update(\Illuminate\Http\Request $request, Customer $customer): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:lessor,lessee,guarantor,prospect,agent,supplier,administrator',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string',
            'document_type' => 'required|string|in:pf,pj',

            // Campos condicionais para Pessoa Física (pf)
            'cpf' => 'required_if:document_type,pf|nullable|string|unique:customers,cpf,' . $customer->id,
            'rg' => 'required_if:document_type,pf|nullable|string',
            'marital_status' => 'required_if:document_type,pf|nullable|string',
            'nationality' => 'required_if:document_type,pf|nullable|string',
            'profession' => 'required_if:document_type,pf|nullable|string',
            'spouse_name' => 'nullable|string',
            'spouse_rg' => 'nullable|string',
            'spouse_cpf' => 'nullable|string',
            'spouse_profession' => 'nullable|string',

            // Campos condicionais para Pessoa Jurídica (pj)
            'company_name' => 'required_if:document_type,pj|nullable|string|max:255',
            'cnpj' => 'required_if:document_type,pj|nullable|string|unique:customers,cnpj,' . $customer->id,
        ]);
        
        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Exclui um cliente do banco de dados.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Cliente excluído com sucesso!');
    }
}