<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Lease;
use App\Models\Customer;
use App\Models\Attendance;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Contagem de atendimentos para o funil
        $funnelData = Attendance::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        $totalAttendances = array_sum($funnelData);

        // Contagem de imóveis, contratos e clientes
        $totalProperties = Property::count();
        $totalLeases = Lease::count();
        $totalCustomers = Customer::count();
        $activeLeases = Lease::where('status', 'active')->count();

        // Dados para os cards de resumo financeiro
        $totalIncome = Payment::where('type', 'income')->where('status', 'paid')->sum('paid_amount');
        $totalExpense = Payment::where('type', 'expense')->where('status', 'paid')->sum('paid_amount');
        $balance = $totalIncome - $totalExpense;

        $unpaidPayments = Payment::whereIn('status', ['pending', 'overdue']);
        $totalReceivable = $unpaidPayments->where('type', 'income')->sum('amount');
        $totalPayable = $unpaidPayments->where('type', 'expense')->sum('amount');

        $overdueCount = Payment::where('status', 'overdue')->count();
        $totalPendingCount = Payment::whereIn('status', ['pending', 'overdue'])->count();
        $defaultRate = ($totalPendingCount > 0) ? ($overdueCount / $totalPendingCount) * 100 : 0;

        // Dados para as novas métricas da aba principal
        $totalLeads = Attendance::where('status', 'Novo Contato')->count();
        $totalPayments = Payment::count();
        $totalPaidPayments = Payment::where('status', 'paid')->count();

        // Dados para o gráfico de fluxo de caixa (últimos 6 meses)
        $monthlyPayments = Payment::query()
            ->select(
                DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'),
                DB::raw('type'),
                DB::raw('SUM(paid_amount) as total')
            )
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get();
        
        $incomeData = $monthlyPayments->where('type', 'income')->pluck('total', 'month');
        $expenseData = $monthlyPayments->where('type', 'expense')->pluck('total', 'month');
        $allMonths = $monthlyPayments->pluck('month')->unique()->sort();

        $cashFlowLabels = $allMonths->map(function($month) {
            return Carbon::createFromFormat('Y-m', $month)->translatedFormat('M/y');
        });
        
        $incomeValues = $allMonths->map(fn($month) => $incomeData[$month] ?? 0);
        $expenseValues = $allMonths->map(fn($month) => $expenseData[$month] ?? 0);

        // Dados para o gráfico de pizza (top 5 despesas)
        $expenseCategories = Payment::query()
            ->select('category', DB::raw('SUM(paid_amount) as total'))
            ->where('type', 'expense')
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        
        $pieChartLabels = $expenseCategories->pluck('category');
        $pieChartData = $expenseCategories->pluck('total');

        // Tabelas de atividade recente
        $latestProperties = Property::with('owner')->latest()->take(5)->get();
        $latestPayments = Payment::with('lease.lessee')
                                 ->whereNotNull('paid_at')
                                 ->latest('paid_at')
                                 ->take(5)
                                 ->get();
        
        $pendingPayments = Payment::with('lease.lessee')
                                  ->whereIn('status', ['pending', 'overdue'])
                                  ->latest()
                                  ->get();
                                  
        $latestIncome = Payment::with('lease.lessee')
                               ->where('type', 'income')
                               ->whereNotNull('paid_at')
                               ->latest('paid_at')
                               ->take(5)
                               ->get();
        
        $latestExpenses = Payment::with('lease.lessee')
                                 ->where('type', 'expense')
                                 ->whereNotNull('paid_at')
                                 ->latest('paid_at')
                                 ->take(5)
                                 ->get();

        // Tabela de vencimentos próximos
        $upcomingPayments = Payment::with('lease.lessee')
            ->whereIn('status', ['pending', 'overdue'])
            ->where('payment_date', '>=', now())
            ->where('payment_date', '<=', now()->addDays(30))
            ->orderBy('payment_date')
            ->get();
            
        // Ranking
        $topIncomes = Payment::select('category', DB::raw('SUM(paid_amount) as total'))
                             ->where('type', 'income')
                             ->whereNotNull('paid_at')
                             ->groupBy('category')
                             ->orderByDesc('total')
                             ->take(5)
                             ->get();
        
        $topExpenses = Payment::select('category', DB::raw('SUM(paid_amount) as total'))
                              ->where('type', 'expense')
                              ->whereNotNull('paid_at')
                              ->groupBy('category')
                              ->orderByDesc('total')
                              ->take(5)
                              ->get();

        return view('tenant.dashboard.index', compact(
            'funnelData', 'totalAttendances', 'totalProperties', 'totalLeases',
            'totalCustomers', 'activeLeases', 'totalLeads', 'totalPayments', 'totalPaidPayments',
            'latestProperties', 'latestPayments', 'pendingPayments',
            'latestIncome', 'latestExpenses', 'balance', 'totalReceivable',
            'totalPayable', 'defaultRate', 'cashFlowLabels', 'incomeValues',
            'expenseValues', 'pieChartLabels', 'pieChartData',
            'upcomingPayments', 'topIncomes', 'topExpenses'
        ));
    }
}