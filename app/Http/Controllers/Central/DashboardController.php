<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->hasRole('master-admin')) {
            $tenants = Tenant::all();
        } else {
            $tenants = $user->tenants;
        }
        return view('central.dashboard.index', compact('tenants'));
    }
}
