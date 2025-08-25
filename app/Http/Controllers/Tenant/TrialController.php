<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    public function index(string $token)
    {
        $tenant = tenant();
        if (! $tenant->initial_migration_complete || empty($tenant->post_signup_login_token) || $tenant->post_signup_login_token !== $token) {
            abort(404);
        }
        
        $user = User::first();
        $user->assignRole('admin');
        
        if (!$user) {
            abort(404);
        }

        auth()->login($user, true);
        $tenant->post_signup_login_token = null;
        $tenant->save();
        return redirect()->route('dashboard');
    }
}
