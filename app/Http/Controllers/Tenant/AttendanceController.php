<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\Property;
use App\Http\Requests\AttendanceStoreRequest;
use App\Http\Requests\AttendanceUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly Attendance $attendance,
        private readonly Property $property,
        private readonly Customer $customer
    ) {}

    /**
     * Exibe a lista de atendimentos.
     */
    public function index(): View
    {
        $attendances = $this->attendance->with(['property', 'customer'])
            ->withCount('history')
            ->latest()
            ->paginate(15);

        return view('tenant.dashboard.attendances.index', compact('attendances'));
    }

    /**
     * Mostra o formulário para criar um novo atendimento.
     */
    public function create(): View
    {
        $properties = $this->property->select(['id', 'code', 'description'])
            ->where('status', 'published')
            ->get();
            
        $customers = $this->customer->select(['id', 'name', 'email'])
            ->orderBy('name')
            ->get();
            
        $statuses = $this->getAvailableStatuses();

        return view('tenant.dashboard.attendances.create', compact('properties', 'customers', 'statuses'));
    }

    /**
     * Armazena um novo atendimento no banco de dados.
     */
    public function store(AttendanceStoreRequest $request): RedirectResponse
    {
        try {
            $attendance = $this->attendance->create($request->validated());
            
            // Cria histórico inicial
            $attendance->history()->create([
                'new_status' => $request->validated()['status'],
                'old_status' => null,
                'new_notes' => $request->validated()['notes'] ?? null,
                'old_notes' => null,
            ]);

            return redirect()
                ->route('attendances.index')
                ->with('success', 'Atendimento cadastrado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar atendimento. Tente novamente.');
        }
    }

    /**
     * Exibe um atendimento específico.
     */
    public function show(Attendance $attendance): View
    {
        $attendance->load(['property', 'customer', 'history' => function ($query) {
            $query->latest();
        }]);

        return view('tenant.dashboard.attendances.show', compact('attendance'));
    }

    /**
     * Mostra o formulário para editar um atendimento.
     */
    public function edit(Attendance $attendance): View
    {
        $properties = $this->property->select(['id', 'code', 'description'])
            ->where('status', 'published')
            ->get();
            
        $customers = $this->customer->select(['id', 'name', 'email'])
            ->orderBy('name')
            ->get();
            
        $statuses = $this->getAvailableStatuses();

        return view('tenant.dashboard.attendances.edit', compact('attendance', 'properties', 'customers', 'statuses'));
    }

    /**
     * Atualiza um atendimento no banco de dados.
     */
    public function update(AttendanceUpdateRequest $request, Attendance $attendance): RedirectResponse
    {
        try {
            $oldStatus = $attendance->status;
            $oldNotes = $attendance->notes;
            
            $attendance->update($request->validated());

            // Cria histórico apenas se houve mudanças significativas
            if ($this->hasSignificantChanges($oldStatus, $oldNotes, $attendance)) {
                $attendance->history()->create([
                    'old_status' => $oldStatus,
                    'new_status' => $attendance->status,
                    'old_notes' => $oldNotes,
                    'new_notes' => $attendance->notes,
                ]);
            }

            return redirect()
                ->route('attendances.index')
                ->with('success', 'Atendimento atualizado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar atendimento. Tente novamente.');
        }
    }

    /**
     * Exclui um atendimento do banco de dados.
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        try {
            $attendance->delete();

            return redirect()
                ->route('attendances.index')
                ->with('success', 'Atendimento excluído com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir atendimento. Tente novamente.');
        }
    }

    /**
     * Retorna os status disponíveis para atendimentos.
     */
    private function getAvailableStatuses(): array
    {
        return [
            'pending' => 'Pendente',
            'in_progress' => 'Em Andamento',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado',
        ];
    }

    /**
     * Verifica se houve mudanças significativas que justificam criar histórico.
     */
    private function hasSignificantChanges(string $oldStatus, ?string $oldNotes, Attendance $attendance): bool
    {
        return $oldStatus !== $attendance->status || $oldNotes !== $attendance->notes;
    }
}