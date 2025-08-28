<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Lease;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Construtor com injeção de dependências.
     *
     * @param Payment $payment
     * @param Lease $lease
     */
    public function __construct(private Payment $payment, private Lease $lease)
    {}
    /**
     * Exibe a lista de pagamentos.
     */
    public function index()
    {
        $payments = $this->payment->with('lease.renter', 'lease.property')->latest()->get();
        
        return view('tenant.dashboard.payments.index', compact('payments'));
    }

    /**
     * Exibe o formulário para criar um novo pagamento.
     */
    public function create()
    {
        // Obtém todos os contratos ativos para o dropdown
        $leases = $this->lease->where('status', 'active')->get();

        return view('tenant.dashboard.payments.create', compact('leases'));
    }

    /**
     * Salva um novo pagamento no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,overdue,pending',
        ]);
        
        $this->payment->create($validated);

        return redirect()->route('payments.index')
                         ->with('status', 'Pagamento registrado com sucesso!');
    }
    
    /**
     * Exibe o formulário para editar um pagamento.
     */
    public function edit(Payment $payment)
    {
        $leases = $this->lease->where('status', 'active')->get();
        return view('tenant.dashboard.payments.edit', compact('payment', 'leases'));
    }

    /**
     * Atualiza um pagamento no banco de dados.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,overdue,pending',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')
                         ->with('status', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove um pagamento do banco de dados.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
                         ->with('status', 'Pagamento excluído com sucesso!');
    }
}