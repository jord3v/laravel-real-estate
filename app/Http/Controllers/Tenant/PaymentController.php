<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use DB;
use Exception;

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

    /**
     * Atualiza um pagamento no banco de dados.
     */
    public function receive(Request $request, Lease $lease, Payment $payment)
    {
        try {
            $validated = $request->validate([
                'paid_at' => 'required|date',
                'paid_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|string',
                'transaction_code' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);
            
            DB::transaction(function () use ($validated, $payment) {
                // A validação agora checa o valor do boleto vs. o valor pago.
                // Se o valor pago for menor, o status é 'partially_overdue'.
                $status = ($validated['paid_amount'] < $payment->amount) ? 'partially_overdue' : 'paid';

                $payment->update([
                    'paid_at' => $validated['paid_at'],
                    'paid_amount' => $validated['paid_amount'],
                    'payment_method' => $validated['payment_method'],
                    'transaction_code' => $validated['transaction_code'],
                    'notes' => $validated['notes'],
                    'status' => $status,
                ]);
            });

            return back()->with('success', 'Pagamento recebido com sucesso!');

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Ocorreu um erro ao receber o pagamento: ' . $e->getMessage()]);
        }
    }
}