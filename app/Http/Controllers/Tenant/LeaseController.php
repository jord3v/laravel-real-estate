<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaseStoreRequest;
use App\Http\Requests\LeaseUpdateRequest;
use App\Models\Customer;
use App\Models\Guarantee;
use App\Models\Lease;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LeaseController extends Controller
{
    /**
     * Lista todos os contratos de locação.
     */
    public function index(): View
    {
        $leases = Lease::with(['lessor', 'lessee', 'property', 'payments'])
            ->latest()
            ->paginate(15);

        return view('tenant.dashboard.leases.index', compact('leases'));
    }

    /**
     * Exibe o formulário de criação de contrato.
     */
    public function create(): View
    {
        $data = $this->getFormData();
        return view('tenant.dashboard.leases.create', $data);
    }

    /**
     * Salva um novo contrato de locação.
     */
    public function store(LeaseStoreRequest $request): RedirectResponse
    {
        try {
            $data = $this->prepareLeaseData($request);

            DB::transaction(function () use ($data) {
                $lease = Lease::create($data);
                $this->createGuarantee($lease, $data['guarantee_type']);
            });

            return redirect()
                ->route('leases.index')
                ->with('success', 'Contrato cadastrado com sucesso!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar contrato. Tente novamente.');
        }
    }

    /**
     * Exibe os detalhes de um contrato específico.
     */
    public function show(Lease $lease): View
    {
        $lease->load(['lessor', 'lessee', 'property', 'payments']);
        return view('tenant.dashboard.leases.show', compact('lease'));
    }

    /**
     * Exibe o formulário de edição de contrato.
     */
    public function edit(Lease $lease): View
    {
        $data = $this->getFormData();
        $data['lease'] = $lease;
        
        return view('tenant.dashboard.leases.edit', $data);
    }

    /**
     * Atualiza um contrato existente.
     */
    public function update(LeaseUpdateRequest $request, Lease $lease): RedirectResponse
    {
        try {
            $data = $this->prepareLeaseData($request);

            DB::transaction(function () use ($data, $lease) {
                $lease->update($data);
                $this->updateGuarantee($lease, $data['guarantee_type']);
            });

            return redirect()
                ->route('leases.index')
                ->with('success', 'Contrato atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar contrato. Tente novamente.');
        }
    }

    /**
     * Remove um contrato.
     */
    public function destroy(Lease $lease): RedirectResponse
    {
        try {
            if ($lease->payments()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Não é possível excluir um contrato com pagamentos vinculados.');
            }

            $lease->delete();

            return redirect()
                ->route('leases.index')
                ->with('success', 'Contrato removido com sucesso!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao remover contrato. Tente novamente.');
        }
    }

    /**
     * Simula os pagamentos do contrato antes de salvar.
     */
    public function previewPayments(Lease $lease): JsonResponse
    {
        try {
            $payments = $this->generatePaymentSchedule($lease);
            return response()->json($payments);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar simulação de pagamentos.'
            ], 500);
        }
    }

    /**
     * Salva os pagamentos gerados no banco de dados.
     */
    public function storeGeneratedPayments(Lease $lease, Request $request): RedirectResponse
    {
        try {
            $validated = $this->validatePayments($request);

            DB::transaction(function () use ($validated, $lease) {
                $lease->payments()->createMany($validated['payments']);
            });

            return redirect()
                ->route('leases.show', $lease)
                ->with('success', 'Pagamentos gerados com sucesso!');

        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao salvar pagamentos. Tente novamente.');
        }
    }

    /**
     * Obtém dados necessários para os formulários.
     */
    private function getFormData(): array
    {
        return [
            'lessors' => Customer::where('type', 'lessor')->get(),
            'lessees' => Customer::where('type', 'lessee')->get(),
            'guarantors' => Customer::where('type', 'guarantor')->get(),
            'properties' => Property::all(),
        ];
    }

    /**
     * Prepara os dados do contrato para salvamento.
     */
    private function prepareLeaseData(LeaseStoreRequest|LeaseUpdateRequest $request): array
    {
        $data = $request->validated();
        $data['benfeitorias'] = $request->has('benfeitorias');
        $data['monetary_correction'] = $request->has('monetary_correction');
        $data['end_date'] = Carbon::parse($data['start_date'])
            ->addMonths((int)$data['term_months']);

        return $data;
    }

    /**
     * Cria uma nova garantia para o contrato.
     */
    private function createGuarantee(Lease $lease, string $guaranteeType): void
    {
        Guarantee::create([
            'lease_id' => $lease->id,
            'type' => $guaranteeType,
        ]);
    }

    /**
     * Atualiza a garantia do contrato.
     */
    private function updateGuarantee(Lease $lease, string $guaranteeType): void
    {
        $guarantee = $lease->guarantees()->firstOrNew();
        $guarantee->update(['type' => $guaranteeType]);
    }

    /**
     * Gera o cronograma de pagamentos do contrato.
     */
    private function generatePaymentSchedule(Lease $lease): array
    {
        $startDate = Carbon::parse($lease->start_date);
        $rentAmount = $lease->rent_amount;
        $dueDay = $lease->due_day;
        $termMonths = $lease->term_months;
        $readjustmentIndex = $lease->readjustment_index ?? 'Índice não definido';

        $payments = [];
        $installmentCount = 0;
        $currentDate = $startDate->copy()->addMonth()->setDay($dueDay);

        // Cálculo do primeiro pagamento (pro-rata)
        if ($startDate->day != $dueDay) {
            $payments[] = $this->generateProRataPayment(
                $startDate, 
                $currentDate, 
                $rentAmount, 
                ++$installmentCount
            );
            
            $currentDate = $currentDate->addMonth()->setDay($dueDay);
            $termMonths--;
        }

        // Gerando os pagamentos restantes
        for ($i = 0; $i < $termMonths; $i++) {
            $payments[] = $this->generateRegularPayment(
                $currentDate->copy(),
                $rentAmount,
                ++$installmentCount,
                $readjustmentIndex
            );
            
            $currentDate->addMonth()->setDay($dueDay);
        }

        return $payments;
    }

    /**
     * Gera um pagamento pro-rata.
     */
    private function generateProRataPayment(
        Carbon $startDate, 
        Carbon $paymentDate, 
        float $rentAmount, 
        int $installmentCount
    ): array {
        $daysInFirstMonth = $startDate->diffInDays($startDate->copy()->endOfMonth()) + 1;
        $proRataAmount = ($rentAmount / $startDate->daysInMonth) * $daysInFirstMonth;

        return [
            'amount' => round($proRataAmount, 2),
            'payment_date' => $paymentDate->copy(),
            'reference_date' => $paymentDate->copy(),
            'status' => 'pending',
            'description' => "Aluguel (Pro-rata) - Parcela {$installmentCount} (Ref. {$paymentDate->translatedFormat('F Y')})",
            'type' => 'income',
            'category' => 'Aluguel',
        ];
    }

    /**
     * Gera um pagamento regular.
     */
    private function generateRegularPayment(
        Carbon $paymentDate,
        float $rentAmount,
        int $installmentCount,
        string $readjustmentIndex
    ): array {
        $description = "Aluguel - Parcela {$installmentCount} (Ref. {$paymentDate->translatedFormat('F Y')})";

        if ($installmentCount > 0 && $installmentCount % 12 == 0) {
            $description .= " (Reajuste anual pelo {$readjustmentIndex})";
        }

        return [
            'amount' => $rentAmount,
            'payment_date' => $paymentDate,
            'reference_date' => $paymentDate->copy(),
            'status' => 'pending',
            'description' => $description,
            'type' => 'income',
            'category' => 'Aluguel',
        ];
    }

    /**
     * Valida os dados dos pagamentos.
     */
    private function validatePayments(Request $request): array
    {
        return $request->validate([
            'payments' => 'required|array',
            'payments.*.amount' => 'required|numeric',
            'payments.*.payment_date' => 'required|date',
            'payments.*.reference_date' => 'required|date',
            'payments.*.status' => 'required|string',
            'payments.*.type' => 'required|string',
            'payments.*.category' => 'required|string',
            'payments.*.description' => 'required|string',
        ]);
    }
}