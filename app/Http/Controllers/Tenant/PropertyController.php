<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiDescriptionRequest;
use App\Http\Requests\PropertyFormRequest;
use App\Models\Customer;
use App\Models\Property;
use App\Services\Gemini;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class PropertyController extends Controller
{
    public function __construct(
        private Property $property,
        private Customer $customer
    ) {}

    public function index(): View
    {
        $properties = $this->property->with('owner')->latest()->paginate(15);
        return view('tenant.dashboard.properties.index', compact('properties'));
    }

    public function create(): View
    {
        $owners = $this->customer->all();
        return view('tenant.dashboard.properties.create', compact('owners'));
    }

    public function store(PropertyFormRequest $request): RedirectResponse
    {
        $this->property->create($request->validated());
        return redirect()->route('properties.index')->with('status', 'Imóvel cadastrado com sucesso!');
    }

    public function edit(Property $property): View
    {
        $owners = $this->customer->all();
        return view('tenant.dashboard.properties.edit', compact('property', 'owners'));
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