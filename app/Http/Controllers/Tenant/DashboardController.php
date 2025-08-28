<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Lease;
use App\Models\Customer;
use App\Models\Attendance;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Construtor com injeção de dependências.
     *
     * @param Property $property
     * @param Lease $lease
     * @param Customer $customer
     * @param Attendance $attendance
     * @param Payment $payment    
     */
    public function __construct(private Property $property, private Lease $lease, private Customer $customer, private Attendance $attendance, private Payment $payment)
    {}
    public function index()
    {
        // Contagem de atendimentos para o funil
        $funnelData = $this->attendance->query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        $totalAttendances = array_sum($funnelData);

        // Contagem de imóveis, contratos e clientes
        $totalProperties = $this->property->count();
        $totalLeases = $this->lease->count();
        $activeLeases = $this->lease->where('status', 'active')->count(); // Mantido para o card de contratos ativos
        
        // **NOVO** - Contagem de todos os clientes
        $totalRenters = $this->customer->count();

        // Busca os últimos imóveis e pagamentos para as tabelas
        $latestProperties = $this->property->with('owner')->latest()->take(5)->get();
        $latestPayments = $this->payment->with('lease.renter')->latest()->take(5)->get();

        return view('tenant.dashboard.index', compact(
            'funnelData',
            'totalAttendances',
            'totalProperties',
            'totalLeases',
            'activeLeases',
            'totalRenters', // Variável agora passada para a view
            'latestProperties',
            'latestPayments'
        ));
    }
}