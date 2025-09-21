<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    /**
     * Exibe a página inicial do website.
     */
    public function index(Request $request): View
    {
        $data = $this->getCommonData();
        return view('tenant.website.index', $data);
    }

    /**
     * Exibe a página de pesquisa de imóveis.
     */
    public function search(Request $request): View
    {
        $data = $this->getCommonData();
        return view('tenant.website.search', $data);
    }

    /**
     * Exibe a página individual do imóvel por ID.
     */
    public function show(int $id): View
    {
        try {
            $property = Property::published()->findOrFail($id);
            $tenant = tenant();

            return view('tenant.website.property', compact('property', 'tenant'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Imóvel não encontrado ou indisponível.');
        }
    }

    /**
     * Obtém dados comuns para as views do website.
     */
    private function getCommonData(): array
    {
        return [
            'tenant' => tenant(),
            'types' => Type::active()->ordered()->get(),
        ];
    }
}
