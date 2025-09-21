<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentReceiveRequest;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\PaymentUpdateRequest;
use App\Models\Lease;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private readonly Payment $payment,
        private readonly Lease $lease
    ) {}

    /**
     * Lista todos os pagamentos.
     */
    public function index(): View
    {
        $payments = $this->payment
            ->with(['lease.lessee', 'lease.property'])
            ->latest()
            ->paginate(20);

        return view('tenant.dashboard.payments.index', compact('payments'));
    }

    /**
     * Exibe o formulário de criação de pagamento.
     */
    public function create(): View
    {
        $leases = $this->getActiveLeases();
        return view('tenant.dashboard.payments.create', compact('leases'));
    }

    /**
     * Salva um novo pagamento.
     */
    public function store(PaymentStoreRequest $request): RedirectResponse
    {
        try {
            $this->payment->create($request->validated());

            return redirect()
                ->route('payments.index')
                ->with('success', 'Pagamento registrado com sucesso!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao registrar pagamento. Tente novamente.');
        }
    }

    /**
     * Exibe o formulário de edição de pagamento.
     */
    public function edit(Payment $payment): View
    {
        $leases = $this->getActiveLeases();
        return view('tenant.dashboard.payments.edit', compact('payment', 'leases'));
    }

    /**
     * Atualiza um pagamento existente.
     */
    public function update(PaymentUpdateRequest $request, Payment $payment): RedirectResponse
    {
        try {
            $payment->update($request->validated());

            return redirect()
                ->route('payments.index')
                ->with('success', 'Pagamento atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar pagamento. Tente novamente.');
        }
    }

    /**
     * Remove um pagamento do banco de dados.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        try {
            if ($payment->status === 'paid') {
                return redirect()
                    ->back()
                    ->with('error', 'Não é possível excluir um pagamento já quitado.');
            }

            $payment->delete();

            return redirect()
                ->route('payments.index')
                ->with('success', 'Pagamento excluído com sucesso!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir pagamento. Tente novamente.');
        }
    }

    /**
     * Processa o recebimento de um pagamento.
     */
    public function receive(PaymentReceiveRequest $request, Lease $lease, Payment $payment): RedirectResponse
    {
        try {
            $this->processPaymentReceipt($payment, $request->validated());

            return redirect()
                ->back()
                ->with('success', 'Pagamento recebido com sucesso!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao processar recebimento. Tente novamente.');
        }
    }

    /**
     * Obtém contratos ativos para os formulários.
     */
    private function getActiveLeases()
    {
        return $this->lease
            ->where('status', 'active')
            ->with(['lessee', 'property'])
            ->get();
    }

    /**
     * Processa o recebimento do pagamento em uma transação.
     */
    private function processPaymentReceipt(Payment $payment, array $data): void
    {
        DB::transaction(function () use ($payment, $data) {
            $status = $this->calculatePaymentStatus($payment, $data['paid_amount']);
            
            $payment->update([
                'paid_at' => $data['paid_at'],
                'paid_amount' => $data['paid_amount'],
                'payment_method' => $data['payment_method'],
                'transaction_code' => $data['transaction_code'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => $status,
            ]);
        });
    }

    /**
     * Calcula o status do pagamento baseado no valor pago.
     */
    private function calculatePaymentStatus(Payment $payment, float $paidAmount): string
    {
        if ($paidAmount >= $payment->amount) {
            return 'paid';
        }
        
        if ($paidAmount > 0) {
            return 'partially_paid';
        }
        
        return $payment->status; // Mantém o status atual se não foi pago nada
    }
}