<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\CentralUser;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    /**
     * Exibe o formulário de teste gratuito.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('central.trial');
    }

    /**
     * Processa o formulário de registro de teste gratuito.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $token = str()->uuid();
        $subdomain = "client1." . request()->getHost();
        $user = CentralUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        User::find($user->id)->assignRole('tenant');
        $tenant = Tenant::create([
            'name' => $request->company_name,
            'email' => $request->email,
            'post_signup_login_token' => $token,
            'initial_migration_complete' => false
        ]);
        $tenant->domains()->create(['domain' => $subdomain]);
        $user->tenants()->attach($tenant);
        return redirect()->route('_post_tenant_signup', ['token' => $token])->domain($subdomain);
    }
}
