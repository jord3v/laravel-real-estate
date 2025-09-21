<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Lease;
use App\Models\Customer;
use App\Models\Attendance;
use App\Models\Payment;
use App\Http\Requests\UpdateTenantRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal com métricas e estatísticas.
     */
    public function index(): View
    {
        $data = $this->getDashboardData();
        return view('tenant.dashboard.index', $data);
    }

    /**
     * Mostra o formulário de edição do tenant.
     */
    public function edit(): View
    {
        $tenant = tenant();
        $themes = $this->getBootswatchThemes();

        return view('tenant.dashboard.edit', compact('tenant', 'themes'));
    }

    /**
     * Atualiza os dados do tenant.
     */
    public function update(UpdateTenantRequest $request): RedirectResponse
    {
        try {
            $tenant = tenant();
            $data = $this->prepareUpdateData($request);

            $tenant->update($data);
            $tenant->save();

            return redirect()
                ->route('tenant.dashboard.edit')
                ->with('success', 'Dados do tenant atualizados com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar dados. Tente novamente.');
        }
    }

    /**
     * Remove o modo manutenção do tenant.
     */
    public function disableMaintenanceMode(): RedirectResponse
    {
        try {
            $tenant = tenant();
            $tenant->update(['maintenance_mode' => null]);

            return redirect()
                ->route('tenant.dashboard.edit')
                ->with('success', 'Modo manutenção desativado!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao desativar modo manutenção.');
        }
    }

    /**
     * Coleta todos os dados necessários para o dashboard.
     */
    private function getDashboardData(): array
    {
        return array_merge(
            $this->getAttendanceMetrics(),
            $this->getPropertyMetrics(),
            $this->getFinancialMetrics(),
            $this->getCashFlowData(),
            $this->getRecentActivity(),
            $this->getUpcomingPayments(),
            $this->getRankingData()
        );
    }

    /**
     * Métricas de atendimento.
     */
    private function getAttendanceMetrics(): array
    {
        $funnelData = Attendance::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'funnelData' => $funnelData,
            'totalAttendances' => array_sum($funnelData),
            'totalLeads' => Attendance::where('status', 'Novo Contato')->count(),
        ];
    }

    /**
     * Métricas de propriedades e contratos.
     */
    private function getPropertyMetrics(): array
    {
        return [
            'totalProperties' => Property::count(),
            'totalLeases' => Lease::count(),
            'activeLeases' => Lease::where('status', 'active')->count(),
            'totalCustomers' => Customer::count(),
        ];
    }

    /**
     * Métricas financeiras.
     */
    private function getFinancialMetrics(): array
    {
        $totalIncome = Payment::where('type', 'income')
            ->where('status', 'paid')
            ->sum('paid_amount');
            
        $totalExpense = Payment::where('type', 'expense')
            ->where('status', 'paid')
            ->sum('paid_amount');

        $unpaidPayments = Payment::whereIn('status', ['pending', 'overdue']);
        $totalReceivable = (clone $unpaidPayments)->where('type', 'income')->sum('amount');
        $totalPayable = (clone $unpaidPayments)->where('type', 'expense')->sum('amount');

        $overdueCount = Payment::where('status', 'overdue')->count();
        $totalPendingCount = Payment::whereIn('status', ['pending', 'overdue'])->count();
        $defaultRate = $totalPendingCount > 0 ? ($overdueCount / $totalPendingCount) * 100 : 0;

        return [
            'balance' => $totalIncome - $totalExpense,
            'totalReceivable' => $totalReceivable,
            'totalPayable' => $totalPayable,
            'defaultRate' => $defaultRate,
            'totalPayments' => Payment::count(),
            'totalPaidPayments' => Payment::where('status', 'paid')->count(),
        ];
    }

    /**
     * Dados de fluxo de caixa.
     */
    private function getCashFlowData(): array
    {
        $monthlyPayments = Payment::select([
                DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'),
                'type',
                DB::raw('SUM(paid_amount) as total')
            ])
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get();

        $incomeData = $monthlyPayments->where('type', 'income')->pluck('total', 'month');
        $expenseData = $monthlyPayments->where('type', 'expense')->pluck('total', 'month');
        $allMonths = $monthlyPayments->pluck('month')->unique()->sort();

        $expenseCategories = Payment::select('category', DB::raw('SUM(paid_amount) as total'))
            ->where('type', 'expense')
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return [
            'cashFlowLabels' => $allMonths->map(fn($month) => Carbon::createFromFormat('Y-m', $month)->translatedFormat('M/y')),
            'incomeValues' => $allMonths->map(fn($month) => $incomeData[$month] ?? 0),
            'expenseValues' => $allMonths->map(fn($month) => $expenseData[$month] ?? 0),
            'pieChartLabels' => $expenseCategories->pluck('category'),
            'pieChartData' => $expenseCategories->pluck('total'),
        ];
    }

    /**
     * Atividades recentes.
     */
    private function getRecentActivity(): array
    {
        return [
            'latestProperties' => Property::with('owner')->latest()->take(5)->get(),
            'latestPayments' => Payment::with('lease.lessee')
                ->whereNotNull('paid_at')
                ->latest('paid_at')
                ->take(5)
                ->get(),
            'pendingPayments' => Payment::with('lease.lessee')
                ->whereIn('status', ['pending', 'overdue'])
                ->latest()
                ->get(),
            'latestIncome' => Payment::with('lease.lessee')
                ->where('type', 'income')
                ->whereNotNull('paid_at')
                ->latest('paid_at')
                ->take(5)
                ->get(),
            'latestExpenses' => Payment::with('lease.lessee')
                ->where('type', 'expense')
                ->whereNotNull('paid_at')
                ->latest('paid_at')
                ->take(5)
                ->get(),
        ];
    }

    /**
     * Pagamentos próximos do vencimento.
     */
    private function getUpcomingPayments(): array
    {
        return [
            'upcomingPayments' => Payment::with('lease.lessee')
                ->whereIn('status', ['pending', 'overdue'])
                ->where('payment_date', '>=', now())
                ->where('payment_date', '<=', now()->addDays(30))
                ->orderBy('payment_date')
                ->get(),
        ];
    }

    /**
     * Dados de ranking.
     */
    private function getRankingData(): array
    {
        return [
            'topIncomes' => Payment::select('category', DB::raw('SUM(paid_amount) as total'))
                ->where('type', 'income')
                ->whereNotNull('paid_at')
                ->groupBy('category')
                ->orderByDesc('total')
                ->take(5)
                ->get(),
            'topExpenses' => Payment::select('category', DB::raw('SUM(paid_amount) as total'))
                ->where('type', 'expense')
                ->whereNotNull('paid_at')
                ->groupBy('category')
                ->orderByDesc('total')
                ->take(5)
                ->get(),
        ];
    }

    /**
     * Busca temas do Bootswatch sem cache.
     */
    private function getBootswatchThemes(): array
    {
        try {
            $response = Http::timeout(10)->get('https://bootswatch.com/api/5.json');
            
            if ($response->successful()) {
                return $response->json('themes', []);
            }
        } catch (\Exception $e) {
            // Log error silently
        }

        return [];
    }

    /**
     * Prepara os dados para atualização do tenant.
     */
    private function prepareUpdateData(UpdateTenantRequest $request): array
    {
        $data = $request->validated();

        // Processa telefones
        $data['phones'] = $this->preparePhones($request);

        // Processa endereço
        $data['address'] = $request->input('address', []);

        // Processa horários de atendimento
        $data['business_hours'] = $request->input('business_hours', []);

        // Processa redes sociais
        $data['social'] = $request->input('social', []);

        // Processa modo manutenção
        if ($request->has('maintenance_mode')) {
            $data['maintenance_mode'] = [
                'time' => time(),
                'retry' => null,
                'allowed' => [],
                'message' => 'O sistema está em manutenção. Voltamos em breve!'
            ];
        }

        return $data;
    }

    /**
     * Prepara array de telefones com informação de WhatsApp.
     */
    private function preparePhones(UpdateTenantRequest $request): array
    {
        $phones = $request->input('phones', []);
        $phonesWhatsapp = $request->input('phones_whatsapp', []);
        $phonesArray = [];

        foreach ($phones as $idx => $number) {
            if (!empty($number)) {
                $phonesArray[] = [
                    'number' => $number,
                    'whatsapp' => in_array($idx, (array)$phonesWhatsapp),
                ];
            }
        }

        return $phonesArray;
    }
}