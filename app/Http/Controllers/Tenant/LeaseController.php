<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lease;
use App\Models\Property;
use Illuminate\Http\Request;

class LeaseController extends Controller
{
    /**
     * Construtor com injeção de dependências.
     *
     * @param Customer $customer
     * @param Lease $lease
     * @param Property $property
     */
    public function __construct(private Customer $customer, private Lease $lease, private Property $property)
    {}
    /**
     * Exibe a lista de contratos.
     */
    public function index()
    {
        $leases = $this->lease->with(['property', 'renter'])->latest()->get();
        
        return view('tenant.dashboard.leases.index', compact('leases'));
    }

    /**
     * Exibe o formulário para criar um novo contrato.
     */
    public function create()
    {
        // Obtém todos os imóveis disponíveis
        $properties = $this->property->all();
        // Obtém apenas as pessoas que são do tipo 'renter'
        $renters = $this->customer->where('type', 'renter')->get();

        return view('tenant.dashboard.leases.create', compact('properties', 'renters'));
    }

    /**
     * Salva um novo contrato no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'renter_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric',
        ]);
        
        // A coluna 'owner_id' é preenchida automaticamente com base no imóvel selecionado
        $property = $this->property->findOrFail($validated['property_id']);
        $validated['owner_id'] = $property->owner_id;

        $this->lease->create($validated);

        return redirect()->route('leases.index')
                         ->with('status', 'Contrato cadastrado com sucesso!');
    }
}