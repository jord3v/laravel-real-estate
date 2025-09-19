<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\AttendanceController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\LeaseController;
use App\Http\Controllers\Tenant\PaymentController;
use App\Http\Controllers\Tenant\PropertyController;
use App\Http\Controllers\Tenant\TrialController;
use App\Http\Controllers\Tenant\WebsiteController;
use App\Http\Controllers\Tenant\FavoriteController;
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
        // Rota pública para retornar imóveis em JSON
        Route::get('/api/properties', [PropertyController::class, 'publicJson']);
            // Rota pública para página individual do imóvel
            
    
        Route::middleware(['maintenance'])->group(function () {
            Route::get('/', [WebsiteController::class, 'index']);
            Route::get('/search', [WebsiteController::class, 'search']);
            Route::get('/property/{id}', [PropertyController::class, 'show'])->name('property.show');
        });  
    Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/edit', [DashboardController::class, 'edit'])->name('tenant.dashboard.edit');
    Route::put('/update', [DashboardController::class, 'update'])->name('tenant.dashboard.update');
    Route::post('/disable-maintenance', [App\Http\Controllers\Tenant\DashboardController::class, 'disableMaintenanceMode'])->name('dashboard.disable_maintenance');
        Route::prefix('properties')->name('properties.')->group(function () {
            Route::resource('/', PropertyController::class)->names('')->parameters(['' => 'property']);
            Route::post('/generate-ai-description', [PropertyController::class, 'generateDescriptionWithAi'])->name('generateDescriptionWithAi');
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
        Route::get('leases/{lease}/payments/preview', [App\Http\Controllers\Tenant\LeaseController::class, 'previewPayments'])->name('leases.payments.preview');
        Route::post('leases/{lease}/payments/store-generated', [App\Http\Controllers\Tenant\LeaseController::class, 'storeGeneratedPayments'])->name('leases.payments.store_generated');
        Route::post('leases/{lease}/payments/{payment}/receive', [App\Http\Controllers\Tenant\PaymentController::class, 'receive'])->name('leases.payments.receive');
        
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

    // Rotas para favoritos via sessão/cookie
    Route::post('/api/favorites/toggle', [FavoriteController::class, 'toggle']);
    Route::get('/api/favorites/properties', [FavoriteController::class, 'properties']);
});