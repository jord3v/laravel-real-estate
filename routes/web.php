<?php

use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\Central\ImpersonateController;
use App\Http\Controllers\Central\TrialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        
        Route::middleware(['auth'])->prefix('dashboard')->as('dashboard.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('index');
            Route::get('tenants/impersonate/{tenant}/{user}', [ImpersonateController::class, 'impersonate'])->name('impersonate');
            Route::post('tenants/tenant-migrate', [DashboardController::class, 'runTenantMigration'])->name('run.tenant.migrate');

        });        
        
        Route::get('/teste-gratis', [TrialController::class, 'index'])->name('trial.index');
        Route::post('/teste-gratis', [TrialController::class, 'register'])->name('trial.register');
    });
}
