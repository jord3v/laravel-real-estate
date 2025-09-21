<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TrialController extends Controller
{
    public function __construct(
        private readonly User $user
    ) {}

    /**
     * Processa o login automático após criação do tenant.
     */
    public function index(string $token): RedirectResponse
    {
        try {
            $tenant = tenant();
            
            $this->validateTenantState($tenant, $token);
            $user = $this->getFirstUser();
            $this->setupUserAndLogin($user, $tenant);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Valida o estado do tenant e token.
     */
    private function validateTenantState($tenant, string $token): void
    {
        if (!$tenant->initial_migration_complete || 
            empty($tenant->post_signup_login_token) || 
            $tenant->post_signup_login_token !== $token) {
            throw new \Exception('Invalid tenant state or token');
        }
    }

    /**
     * Obtém o primeiro usuário do tenant.
     */
    private function getFirstUser(): User
    {
        $user = $this->user->first();
        
        if (!$user) {
            throw new \Exception('No user found');
        }
        
        return $user;
    }

    /**
     * Configura o usuário como admin e faz login.
     */
    private function setupUserAndLogin(User $user, $tenant): void
    {
        $user->assignRole('admin');
        Auth::login($user, true);
        
        // Limpa o token de acesso único
        $tenant->post_signup_login_token = null;
        $tenant->save();
    }
}
