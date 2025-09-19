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
    /**
     * Exibe a página individual do imóvel por ID.
     */
    public function show($id)
    {
        $property = \App\Models\Property::findOrFail($id);
        $tenant = tenant();
        return view('tenant.website.property', compact('property', 'tenant'));
    }
}
