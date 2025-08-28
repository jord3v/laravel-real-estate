<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\AttendanceController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\LeaseController;
use App\Http\Controllers\Tenant\PaymentController;
use App\Http\Controllers\Tenant\PropertyController;
use App\Http\Controllers\Tenant\TrialController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Features\UserImpersonation;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Aqui você pode registrar as rotas do tenant para a sua aplicação.
| Essas rotas são carregadas pelo TenantRouteServiceProvider.
|
| Sinta-se à vontade para personalizá-las como quiser. Boa sorte!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::middleware(['auth'])->prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::prefix('properties')->name('properties.')->group(function () {
            Route::resource('/', PropertyController::class)->names('')->parameters(['' => 'property']);
            Route::post('{property}/clone', [PropertyController::class, 'clone'])->name('clone');
            Route::post('{property}/media', [PropertyController::class, 'mediaUpload'])->name('media.upload');
            Route::delete('{property}/media/{media}', [PropertyController::class, 'mediaDelete'])->name('media.delete');
            Route::get('{property}/media', [PropertyController::class, 'getMedia'])->name('media.get');
            Route::post('{property}/media/{media}/main', [PropertyController::class, 'setMainMedia'])->name('media.set-main');
            Route::post('{property}/media/sort', [PropertyController::class, 'sortMedia'])->name('media.sort');
        });

        // Agrupando os outros recursos em uma única linha para clareza
        Route::resource('attendances', AttendanceController::class)->names('attendances');
        Route::resource('leases', LeaseController::class)->names('leases');
        Route::resource('customers', CustomerController::class)->names('customers');
        Route::resource('payments', PaymentController::class)->names('payments');
    });
    
    Route::middleware(['universal'])->group(function () {
        Auth::routes();
    });

    Route::get('/signup/{token}', [TrialController::class, 'index'])->name('_post_tenant_signup');
    Route::get('impersonate/{token}', function ($token) {
        return UserImpersonation::makeResponse($token);
    })->name('impersonate');
});