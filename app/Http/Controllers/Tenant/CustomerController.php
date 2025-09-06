<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;

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

    public function store(CustomerStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
    Customer::create($request->validated());
    return redirect()->route('customers.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function edit(Customer $customer): \Illuminate\View\View
    {
        return view('tenant.dashboard.customers.edit', compact('customer'));
    }

    public function update(CustomerUpdateRequest $request, Customer $customer): \Illuminate\Http\RedirectResponse
    {
    $customer->update($request->validated());
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