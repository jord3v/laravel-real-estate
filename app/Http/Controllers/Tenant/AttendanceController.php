<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\Property;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Construtor com injeção de dependências.
     *
     * @param Attendance $attendance
     * @param Property $property
     * @param Customer $customer
     */
    public function __construct(private Attendance $attendance, private Property $property, private Customer $customer)
    {}
    /**
     * Exibe a lista de atendimentos.
     */
    public function index()
    {
        $attendances = $this->attendance->with(['property', 'customer'])
                                 ->withCount('history')
                                 ->latest()
                                 ->paginate(10);

        return view('tenant.dashboard.attendances.index', compact('attendances'));
    }

    /**
     * Exibe o formulário para criar um novo atendimento.
     */
    public function create()
    {
        $properties = $this->property->all();
        $customers = $this->customer->all();
        $statuses = $this->attendance->distinct()->pluck('status'); // Pega os status únicos
        
        return view('tenant.dashboard.attendances.create', compact('properties', 'customers', 'statuses'));
    }

    /**
     * Salva um novo atendimento no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|string|max:255', // Validação agora é apenas 'string'
            'notes' => 'nullable|string',
            'attended_at' => 'nullable|date',
        ]);

        $attendance = $this->attendance->create($validated);
        
        $attendance->history()->create(['new_status' => $validated['status']]);

        return redirect()->route('attendances.index')->with('success', 'Atendimento cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um atendimento específico.
     */
    public function show(Attendance $attendance)
    {
        return view('tenant.dashboard.attendances.show', compact('attendance'));
    }

    /**
     * Exibe o formulário para editar um atendimento.
     */
    public function edit(Attendance $attendance)
    {
        $properties = $this->property->all();
        $customers = $this->customer->all();
        $statuses = $this->attendance->distinct()->pluck('status'); // Pega os status únicos
        
        return view('tenant.dashboard.attendances.edit', compact('attendance', 'properties', 'customers', 'statuses'));
    }

    /**
     * Atualiza um atendimento no banco de dados.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'attended_at' => 'nullable|date',
        ]);
        
        $oldStatus = $attendance->status;
        $oldNotes = $attendance->notes;

        $attendance->update($validated);

        if ($oldStatus !== $attendance->status || $oldNotes !== $attendance->notes) {
            $attendance->history()->create([
                'old_status' => $oldStatus,
                'new_status' => $attendance->status,
                'old_notes' => $oldNotes,
                'new_notes' => $attendance->notes,
            ]);
        }

        return redirect()->route('attendances.index')->with('success', 'Atendimento atualizado com sucesso!');
    }

    /**
     * Exclui um atendimento do banco de dados.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Atendimento excluído com sucesso!');
    }
}