<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebsiteController extends Controller
{
    public function index(Request $request)
    {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    }
}
