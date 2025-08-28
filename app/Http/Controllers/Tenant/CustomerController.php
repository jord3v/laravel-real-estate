<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Construtor com injeção de dependências.
     *
     * @param Customer $customer
     */
    public function __construct(private Customer $customer)
    {}
    /**
     * Exibe a lista de pessoas.
     */
    public function index()
    {
        $customers = $this->customer->all();
        
        return view('tenant.dashboard.customers.index', compact('customers'));
    }

    /**
     * Exibe o formulário para criar uma nova pessoa.
     */
    public function create()
    {
        return view('tenant.dashboard.customers.create');
    }

    /**
     * Salva uma nova pessoa no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:owner,renter,agent', // Valida o tipo
        ]);

        $this->customer->create($validated);

        return redirect()->route('customers.index')
                         ->with('status', 'Pessoa cadastrada com sucesso!');
    }
    
    /**
     * Exibe os detalhes de uma pessoa (redireciona para o formulário de edição).
     */
    public function show(Customer $customer)
    {
        return redirect()->route('customers.edit', $customer);
    }

    /**
     * Exibe o formulário para editar uma pessoa.
     */
    public function edit(Customer $customer)
    {
        return view('tenant.dashboard.customers.edit', compact('customer'));
    }

    /**
     * Atualiza uma pessoa no banco de dados.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$customer->id, // Ignora o próprio ID
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:owner,renter,agent',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
                         ->with('status', 'Pessoa atualizada com sucesso!');
    }

    /**
     * Remove uma pessoa do banco de dados.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
                         ->with('status', 'Pessoa excluída com sucesso!');
    }
}