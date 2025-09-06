<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Lease;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\PaymentUpdateRequest;
use App\Http\Requests\PaymentReceiveRequest;
use DB;
use Exception;

class PaymentController extends Controller
{
    public function __construct(
        private Payment $payment,
        private Lease $lease
    ) {}

    public function index(): \Illuminate\View\View
    {
        $payments = $this->payment->with('lease.renter', 'lease.property')->latest()->get();
        return view('tenant.dashboard.payments.index', compact('payments'));
    }

    public function create(): \Illuminate\View\View
    {
        $leases = $this->lease->where('status', 'active')->get();
        return view('tenant.dashboard.payments.create', compact('leases'));
    }

    public function store(PaymentStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
    $this->payment->create($request->validated());
    return redirect()->route('payments.index')->with('status', 'Pagamento registrado com sucesso!');
    }

    public function edit(Payment $payment): \Illuminate\View\View
    {
        $leases = $this->lease->where('status', 'active')->get();
        return view('tenant.dashboard.payments.edit', compact('payment', 'leases'));
    }

    public function update(PaymentUpdateRequest $request, Payment $payment): \Illuminate\Http\RedirectResponse
    {
    $payment->update($request->validated());
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
    public function receive(PaymentReceiveRequest $request, Lease $lease, Payment $payment)
    {
        try {
            DB::transaction(function () use ($request, $payment) {
                $data = $request->validated();
                $status = ($data['paid_amount'] < $payment->amount) ? 'partially_overdue' : 'paid';
                $payment->update([
                    'paid_at' => $data['paid_at'],
                    'paid_amount' => $data['paid_amount'],
                    'payment_method' => $data['payment_method'],
                    'transaction_code' => $data['transaction_code'],
                    'notes' => $data['notes'],
                    'status' => $status,
                ]);
            });

            return back()->with('success', 'Pagamento recebido com sucesso!');

        // Removido catch de ValidationException, pois o FormRequest já lida com erros de validação automaticamente
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Ocorreu um erro ao receber o pagamento: ' . $e->getMessage()]);
        }
    }
}