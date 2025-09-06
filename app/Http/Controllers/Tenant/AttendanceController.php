<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\Property;
use App\Http\Requests\AttendanceStoreRequest;
use App\Http\Requests\AttendanceUpdateRequest;

class AttendanceController extends Controller
{
    public function __construct(
        private Attendance $attendance,
        private Property $property,
        private Customer $customer
    ) {}

    public function index(): \Illuminate\View\View
    {
        $attendances = $this->attendance->with(['property', 'customer'])
            ->withCount('history')
            ->latest()
            ->paginate(10);
        return view('tenant.dashboard.attendances.index', compact('attendances'));
    }

    public function create(): \Illuminate\View\View
    {
        $properties = $this->property->all();
        $customers = $this->customer->all();
        $statuses = $this->attendance->distinct()->pluck('status');
        return view('tenant.dashboard.attendances.create', compact('properties', 'customers', 'statuses'));
    }

    public function store(AttendanceStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
    $attendance = $this->attendance->create($request->validated());
    $attendance->history()->create(['new_status' => $request->validated()['status']]);
    return redirect()->route('attendances.index')->with('success', 'Atendimento cadastrado com sucesso!');
    }

    public function show(Attendance $attendance): \Illuminate\View\View
    {
        return view('tenant.dashboard.attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance): \Illuminate\View\View
    {
        $properties = $this->property->all();
        $customers = $this->customer->all();
        $statuses = $this->attendance->distinct()->pluck('status'); // Pega os status únicos
        
        return view('tenant.dashboard.attendances.edit', compact('attendance', 'properties', 'customers', 'statuses'));
    }

    /**
     * Atualiza um atendimento no banco de dados.
     */
    public function update(AttendanceUpdateRequest $request, Attendance $attendance)
    {
    $oldStatus = $attendance->status;
    $oldNotes = $attendance->notes;
    $attendance->update($request->validated());

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