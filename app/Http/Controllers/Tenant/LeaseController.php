<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Customer;
use App\Models\Guarantee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\LeaseStoreRequest;
use App\Http\Requests\LeaseUpdateRequest;
use App\Models\Payment;
use Exception;

class LeaseController extends Controller
{
    /**
     * Exibe o formulário de edição de contrato.
     */
    public function edit(Lease $lease)
    {
        $lessors = Customer::where('type', 'lessor')->get();
        $lessees = Customer::where('type', 'lessee')->get();
        $guarantors = Customer::where('type', 'guarantor')->get();
        $properties = Property::all();
        return view('tenant.dashboard.leases.edit', compact('lease', 'lessors', 'lessees', 'guarantors', 'properties'));
    }
    /**
     * Salva um novo contrato de locação.
     */
    public function store(LeaseStoreRequest $request)
    {
        $data = $request->validated();
        $data['benfeitorias'] = $request->has('benfeitorias');
        $data['monetary_correction'] = $request->has('monetary_correction');
        $data['end_date'] = Carbon::parse($data['start_date'])->addMonths((int)$data['term_months']);
        DB::transaction(function () use ($data) {
            $lease = Lease::create($data);
            Guarantee::create([
                'lease_id' => $lease->id,
                'type' => $data['guarantee_type'],
            ]);
        });
        return redirect()->route('leases.index')->with('success', 'Contrato cadastrado com sucesso!');
    }
    /**
     * Exibe o formulário de criação de contrato.
     */
    public function create()
    {
        $lessors = Customer::where('type', 'lessor')->get();
        $lessees = Customer::where('type', 'lessee')->get();
        $guarantors = Customer::where('type', 'guarantor')->get();
        $properties = Property::all();
        return view('tenant.dashboard.leases.create', compact('lessors', 'lessees', 'guarantors', 'properties'));
    }
    /**
     * Lista todos os contratos de locação.
     */
    public function index()
    {
        $leases = Lease::with(['lessor', 'lessee', 'property', 'payments'])
            ->latest()
            ->paginate(15);
        return view('tenant.dashboard.leases.index', compact('leases'));
    }

    public function update(Request $request, Lease $lease)
    {
        try {
            $validated = $request->validate([
                'lessor_id' => 'required|exists:customers,id',
                'lessee_id' => 'required|exists:customers,id',
                'property_id' => 'required|exists:properties,id',
                'contract_type' => 'nullable|string',
                'term_months' => 'required|integer|min:1',
                'start_date' => 'required|date',
                'rent_amount' => 'required|numeric|min:0',
                'due_day' => 'required|integer|min:1|max:31',
                'payment_place' => 'nullable|string',
                'readjustment_index' => 'required|string',
                'alternative_indexes' => 'nullable|array',
                'late_payment_fine_percent' => 'nullable|numeric|min:0',
                'late_payment_fine_limit' => 'nullable|numeric|min:0',
                'late_payment_interest' => 'nullable|numeric|min:0',
                'monetary_correction' => 'nullable|boolean',
                'additional_charges' => 'nullable|array',
                'use_destination' => 'required|string',
                'maintenance_obligations' => 'required|string',
                'benfeitorias' => 'nullable|boolean',
                'guarantee_type' => 'required|string|in:fianca,caucao_dinheiro,seguro_fianca,caucao_imobiliaria',
                'guarantor_id' => 'required_if:guarantee_type,fianca|nullable|exists:customers,id',
                'attorney_fees_percent' => 'required|numeric|min:0',
                'elected_forum' => 'required|string',
                'via_count' => 'required|integer|min:1',
            ]);
            
            $validated['benfeitorias'] = $request->has('benfeitorias');
            $validated['monetary_correction'] = $request->has('monetary_correction');
            $validated['end_date'] = Carbon::parse($validated['start_date'])->addMonths((int)$validated['term_months']);

            DB::transaction(function () use ($validated, $lease) {
                $lease->update($validated);
                
                $guarantee = $lease->guarantees()->firstOrNew();
                $guarantee->update([
                    'type' => $validated['guarantee_type'],
                ]);
            });

            return redirect()->route('leases.index')->with('success', 'Contrato atualizado com sucesso!');

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Ocorreu um erro ao atualizar o contrato: ' . $e->getMessage()]);
        }
    }

    public function show(Lease $lease)
    {
        $lease->load('lessor', 'lessee', 'property', 'payments');
        return view('tenant.dashboard.leases.show', compact('lease'));
    }

    public function destroy(Lease $lease)
    {
        $lease->delete();
        return redirect()->route('leases.index')->with('success', 'Contrato removido com sucesso!');
    }

    /**
     * Simula os pagamentos do contrato antes de salvar.
     */
    public function previewPayments(Lease $lease)
    {
        $startDate = Carbon::parse($lease->start_date);
        $rentAmount = $lease->rent_amount;
        $dueDay = $lease->due_day;
        $termMonths = $lease->term_months;
        $readjustmentIndex = $lease->readjustment_index ?? 'Índice não definido';

        $payments = [];
        $installmentCount = 0;

        // A data de vencimento da primeira parcela
        $currentDate = $startDate->copy()->addMonth()->setDay($dueDay);

        // 1. Cálculo do primeiro pagamento (pro-rata)
        if ($startDate->day != $dueDay) {
            $daysInFirstMonth = $startDate->diffInDays($startDate->copy()->endOfMonth()) + 1;
            $proRataAmount = ($rentAmount / $startDate->daysInMonth) * $daysInFirstMonth;
            $installmentCount++;

            $payments[] = [
                'amount' => round($proRataAmount, 2),
                'payment_date' => $currentDate->copy(),
                'reference_date' => $currentDate->copy(),
                'status' => 'pending',
                'description' => "Aluguel (Pro-rata) - Parcela {$installmentCount} (Ref. {$currentDate->translatedFormat('F Y')})",
                'type' => 'income',
                'category' => 'Aluguel',
            ];

            // Avança para o próximo mês
            $currentDate = $currentDate->addMonth()->setDay($dueDay);
            $termMonths--;
        }

        // 2. Gerando os pagamentos restantes
        for ($i = 0; $i < $termMonths; $i++) {
            $installmentCount++;
            $paymentDate = $currentDate->copy();

            $description = "Aluguel - Parcela {$installmentCount} (Ref. {$paymentDate->translatedFormat('F Y')})";

            if ($installmentCount > 0 && $installmentCount % 12 == 0) {
                $description .= " (Reajuste anual pelo {$readjustmentIndex})";
            }

            $payments[] = [
                'amount' => $rentAmount,
                'payment_date' => $paymentDate,
                'reference_date' => $paymentDate->copy(),
                'status' => 'pending',
                'description' => $description,
                'type' => 'income',
                'category' => 'Aluguel',
            ];
            $currentDate->addMonth()->setDay($dueDay);
        }

        return response()->json($payments);
    }

    
    /**
     * Salva os pagamentos gerados no banco de dados.
     */
    public function storeGeneratedPayments(Lease $lease, Request $request)
    {
        try {
            $validated = $request->validate([
                'payments' => 'required|array',
                'payments.*.amount' => 'required|numeric',
                'payments.*.payment_date' => 'required|date',
                'payments.*.reference_date' => 'required|date', // <-- Adicione esta linha
                'payments.*.status' => 'required|string',
                'payments.*.type' => 'required|string',
                'payments.*.category' => 'required|string',
                'payments.*.description' => 'required|string',
            ]);
            
            DB::transaction(function () use ($validated, $lease) {
                $lease->payments()->createMany($validated['payments']);
            });

            return redirect()->route('leases.show', $lease)->with('success', 'Pagamentos gerados com sucesso!');

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Ocorreu um erro ao salvar: ' . $e->getMessage()]);
        }
    }
}