<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    /**
     * Construtor com injeção de dependências.
     *
     * @param Tenant $tenant
     */
    public function __construct(private Tenant $tenant)
    {}
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->hasRole('master-admin')) {
            $tenants = $this->tenant->all();
        } else {
            $tenants = $user->tenants;
        }
        return view('central.dashboard.index', compact('tenants'));
    }

    /**
     * Executa a migração para todos os tenants.
     */
    public function runTenantMigration()
    {
        try {
            Artisan::call('tenants:migrate');
            return back()->with('status', 'A migração dos tenants foi executada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao executar a migração: ' . $e->getMessage());
        }
    }
}
