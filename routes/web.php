<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

foreach (config('tenancy.central_domains') as $domain) {
        Route::domain($domain)->group(function () {
            Route::get('/', function () {
            return 'Aqui va la pagina principal';
        });
    });
}
