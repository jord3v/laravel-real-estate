<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiDescriptionRequest;
use App\Http\Requests\PropertyFormRequest;
use App\Models\Customer;
use App\Models\Property;
use App\Models\Type;
use App\Services\Gemini;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class PropertyController extends Controller
{
    /**
     * Exibe a página individual do imóvel por ID.
     */
    public function show($id)
    {
        $query = Property::where('id', $id);
        $property = $this->applyPublicationRules($query)->firstOrFail();
            
        $tenant = tenant();
        return view('tenant.website.property', compact('property', 'tenant'));
    }
    public function __construct(
        private Property $property,
        private Customer $customer
    ) {}

    /**
     * Aplica as regras de publicação para imóveis no site público.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyPublicationRules($query)
    {
        $now = now();
        
        return $query
            // Apenas imóveis publicados
            ->where('status', 'published')
            // Deve ter marcado "divulgar no meu site"
            ->where(function ($q) {
                $q->whereJsonContains('publication->my_site', true);
            })
            // Aplicar regras de período
            ->where(function ($q) use ($now) {
                $q->where(function ($subQ) use ($now) {
                    // Cenário 1: Remover manualmente (period_type = 'manual')
                    $subQ->whereJsonContains('publication->period_type', 'manual');
                })->orWhere(function ($subQ) use ($now) {
                    // Cenário 2: Definir prazo (period_type = 'range')
                    $subQ->whereJsonContains('publication->period_type', 'range')
                      ->where(function ($dateQ) use ($now) {
                          // Data de início: deve ser <= data atual
                          $dateQ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(publication, '$.start_date')) <= ?", [$now->format('Y-m-d')])
                               // Data de fim: deve ser >= data atual
                               ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(publication, '$.end_date')) >= ?", [$now->format('Y-m-d')]);
                      });
                });
            });
    }

    /**
     * Retorna os imóveis em formato JSON para o site público.
     */
    public function publicJson(): \Illuminate\Http\JsonResponse
    {
        $perPage = (int) request('per_page', 6);
        $page = (int) request('page', 1);
        
        $query = Property::filter(request()->all())->with('media', 'type');
        $properties = $this->applyPublicationRules($query)
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        $result = $properties->getCollection()->map(function ($property) {
            $images = $property->getMedia('property')->map(fn($media) => $media->getUrl('preview'))->toArray();
            return [
                'id' => $property->id,
                'title' => $property->type->name,
                'code' => $property->code,
                'purpose' => $property->purpose,
                'price' => $property->business_options['sale']['price'] ?? $property->business_options['rental']['price'] ?? $property->business_options['season']['price'] ?? null,
                'location' => $property->address['city'] ?? $property->address['state'] ?? '',
                'description' => $property->description,
                'bedrooms' => $property->compositions['bedrooms'] ?? 0,
                'bathrooms' => $property->compositions['bathrooms'] ?? 0,
                'parking' => $property->compositions['car_spaces'] ?? 0,
                'area' => $property->dimensions['area'] ?? 0,
                'imagens' => $images,
                'type' => $property->type,
            ];
        });
        return response()->json([
            'data' => $result,
            'current_page' => $properties->currentPage(),
            'last_page' => $properties->lastPage(),
            'per_page' => $properties->perPage(),
            'total' => $properties->total(),
        ]);
    }

    public function index(Request $request): View
    {
        $query = $this->property->with('owner', 'type');
        
        // Aplicar filtros
        if ($request->filled('type')) {
            $query->whereHas('type', function ($q) use ($request) {
                $q->where('name', $request->type);
            });
        }
        
        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $properties = $query->latest()->paginate(15);
        $types = Type::active()->ordered()->get();
        
        return view('tenant.dashboard.properties.index', compact('properties', 'types'));
    }

    public function create(): View
    {
        $owners = $this->customer->all();
        $types = Type::active()->ordered()->get();
        return view('tenant.dashboard.properties.create', compact('owners', 'types'));
    }

    public function store(PropertyFormRequest $request): RedirectResponse
    {
    $this->property->create($request->validated());
    return redirect()->route('properties.index')->with('status', 'Imóvel cadastrado com sucesso!');
    }

    public function edit(Property $property): View
    {
        $owners = $this->customer->all();
        $types = Type::active()->ordered()->get();
        return view('tenant.dashboard.properties.edit', compact('property', 'owners', 'types'));
    }

    public function update(PropertyFormRequest $request, Property $property): RedirectResponse
    {
    $property->update($request->validated());
    return redirect()->route('properties.index')->with('status', 'Imóvel atualizado com sucesso!');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $property->delete();
        return redirect()->route('properties.index')->with('status', 'Imóvel excluído com sucesso!');
    }

    public function clone(Property $property): RedirectResponse
    {
        $newProperty = $property->replicate();
        $newProperty->code = "{$property->code}-cópia-" . now()->timestamp;
        $newProperty->status = 'draft';
        $newProperty->save();

        foreach ($property->getMedia('property') as $media) {
            $media->copy($newProperty, $media->collection_name);
        }

        return redirect()->route('properties.index')->with('status', 'Imóvel clonado com sucesso! Você pode editá-lo e adicionar novas mídias.');
    }

    public function mediaUpload(Request $request, Property $property): JsonResponse
    {
        $mediaIds = collect($request->file('file'))->mapWithKeys(function ($file) use ($property) {
            $media = $property->addMedia($file)->toMediaCollection('property');
            return [$file->getClientOriginalName() => $media->id];
        });

        return response()->json(['success' => true, 'media_ids' => $mediaIds]);
    }

    public function getMedia(Property $property): JsonResponse
    {
        $media = $property->getMedia('property')->map(fn($item) => [
            'name' => $item->file_name,
            'size' => $item->size,
            'url' => $item->getUrl('preview'),
            'id' => $item->id,
            'main' => $item->getCustomProperty('is_main', false),
        ]);

        return response()->json($media);
    }

    public function mediaDelete(Property $property, string $mediaId): JsonResponse
    {
        $mediaItem = $property->getMedia('property')->firstWhere('id', $mediaId);

        if (!$mediaItem) {
            return response()->json(['success' => false, 'message' => 'Mídia não encontrada.'], 404);
        }

        $mediaItem->delete();
        return response()->json(['success' => true]);
    }

    public function setMainMedia(Request $request, Property $property, string $mediaId): JsonResponse
    {
        $mediaItem = $property->getMedia('property')->firstWhere('id', $mediaId);

        if (!$mediaItem) {
            return response()->json(['success' => false, 'message' => 'Mídia não encontrada.'], 404);
        }

        $property->getMedia('property')->each(fn($media) => $media->setCustomProperty('is_main', false)->save());
        $mediaItem->setCustomProperty('is_main', true)->save();

        return response()->json(['success' => true]);
    }

    public function sortMedia(Request $request, Property $property): JsonResponse
    {
        foreach ($request->input('order', []) as $index => $mediaId) {
            $property->media()->find($mediaId)?->update(['order_column' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function generateDescriptionWithAi(AiDescriptionRequest $request, Gemini $gemini): JsonResponse
    {
        $description = $gemini->generateDescription($request->validated());

        if ($description) {
            return response()->json(['description' => $description]);
        }

        return response()->json(['error' => 'Não foi possível gerar a descrição. Tente novamente.'], 500);
    }
}