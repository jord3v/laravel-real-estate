<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\PropertyController;

Route::post('/properties', [PropertyController::class, 'apiSearch']);
