<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        private readonly Customer $customer
    ) {}

    /**
     * Exibe a lista de clientes/leads.
     */
    public function index(): View
    {
        $customers = $this->customer
            ->latest()
            ->paginate(15);

        return view('tenant.dashboard.customers.index', compact('customers'));
    }

    /**
     * Mostra o formulário para criar um novo cliente.
     */
    public function create(): View
    {
        return view('tenant.dashboard.customers.create');
    }

    /**
     * Armazena um novo cliente no banco de dados.
     */
    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        try {
            $this->customer->create($request->validated());

            return redirect()
                ->route('customers.index')
                ->with('success', 'Lead cadastrado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar lead. Tente novamente.');
        }
    }

    /**
     * Mostra o formulário para editar um cliente.
     */
    public function edit(Customer $customer): View
    {
        return view('tenant.dashboard.customers.edit', compact('customer'));
    }

    /**
     * Atualiza um cliente no banco de dados.
     */
    public function update(CustomerUpdateRequest $request, Customer $customer): RedirectResponse
    {
        try {
            $customer->update($request->validated());

            return redirect()
                ->route('customers.index')
                ->with('success', 'Lead atualizado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar lead. Tente novamente.');
        }
    }

    /**
     * Exclui um cliente do banco de dados.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        try {
            // Verifica se o cliente tem relacionamentos antes de excluir
            if ($customer->properties()->exists() || $customer->attendances()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Não é possível excluir este lead pois ele possui vínculos no sistema.');
            }

            $customer->delete();

            return redirect()
                ->route('customers.index')
                ->with('success', 'Lead excluído com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir lead. Tente novamente.');
        }
    }
}