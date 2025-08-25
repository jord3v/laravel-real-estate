<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    public function __construct(private Tenant $tenant, private User $user) {}
    public function impersonate($tenant, $user)
    {
        if(!$tenant = $this->tenant->find($tenant))
            return redirect()->back();
        $tenant->run(function ($tenant) use (&$user) {
            $user = $this->user->where('global_id', $user)->first();
        });
        $token = tenancy()->impersonate($tenant, 1, 'dashboard');
        return redirect()->route('impersonate', ['token' => $token])->domain($tenant->domains->first()->domain);
    }
}
