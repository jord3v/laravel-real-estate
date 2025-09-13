<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebsiteController extends Controller
{
    public function index(Request $request)
    {
    $tenant = tenant();
    return view('tenant.website.index', compact('tenant'));
    }

    public function search(Request $request)
    {
    $tenant = tenant();
    return view('tenant.website.search', compact('tenant'));
    }
}
