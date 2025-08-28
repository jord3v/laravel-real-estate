<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyFormRequest;
use App\Models\Customer;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Construtor com injeção de dependências.
     *
     * @param Property $property
     * @param Customer $customer
     */
    public function __construct(private Property $property, private Customer $customer)
    {}

    /**
     * Exibe a lista de imóveis.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $properties = $this->property->with('owner')->latest()->paginate(15);

        return view('tenant.dashboard.properties.index', compact('properties'));
    }

    /**
     * Exibe o formulário para criar um novo imóvel.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $owners = $this->customer->where('type', 'owner')->get();
        return view('tenant.dashboard.properties.create', compact('owners'));
    }

    /**
     * Salva um novo imóvel no banco de dados.
     *
     * @param PropertyFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PropertyFormRequest $request)
    {
        $this->property->create($request->validated());
        return redirect()->route('properties.index')->with('status', 'Imóvel cadastrado com sucesso!');
    }

    /**
     * Exibe o formulário para editar um imóvel.
     *
     * @param Property $property
     * @return \Illuminate\View\View
     */
    public function edit(Property $property)
    {
        $owners = $this->customer->where('type', 'owner')->get();
        return view('tenant.dashboard.properties.edit', compact('property', 'owners'));
    }

    /**
     * Atualiza um imóvel no banco de dados.
     *
     * @param PropertyFormRequest $request
     * @param Property $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PropertyFormRequest $request, Property $property)
    {
        $property->update($request->validated());
        return redirect()->route('properties.index')->with('status', 'Imóvel atualizado com sucesso!');
    }

    /**
     * Remove um imóvel do banco de dados.
     *
     * @param Property $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()
            ->route('properties.index')
            ->with('status', 'Imóvel excluído com sucesso!');
    }

    /**
     * Clona um imóvel existente.
     *
     * @param Property $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clone(Property $property)
    {
        $newProperty = $property->replicate();
        $newProperty->code = $property->code . '-cópia-' . now()->timestamp;
        $newProperty->status = 'draft';
        $newProperty->save();
        foreach ($property->getMedia('property') as $media) {
            $media->copy($newProperty, $media->collection_name);
        }
        return redirect()->route('properties.index')->with('status', 'Imóvel clonado com sucesso! Você pode editá-lo e adicionar novas mídias.');
    }

    /**
     * Faz upload de mídias para um imóvel via Dropzone.
     *
     * @param Request $request
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaUpload(Request $request, Property $property)
    {
        $mediaIds = [];
        foreach ($request->file('file') as $file) {
            $media = $property->addMedia($file)->toMediaCollection('property');
            $mediaIds[$file->getClientOriginalName()] = $media->id;
        }

        return response()->json(['success' => true, 'media_ids' => $mediaIds]);
    }

    /**
     * Retorna as mídias associadas a um imóvel.
     *
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMedia(Property $property)
    {
        $media = $property->getMedia('property')->map(function ($item) {
            return [
                'name' => $item->file_name,
                'size' => $item->size,
                'url' => $item->getUrl('preview'),
                'id' => $item->id,
                'main' => $item->getCustomProperty('is_main', false),
            ];
        });

        return response()->json($media);
    }

    /**
     * Deleta uma mídia de um imóvel.
     *
     * @param Property $property
     * @param string $mediaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaDelete(Property $property, string $mediaId)
    {
        $mediaItem = $property->getMedia('property')->firstWhere('id', $mediaId);

        if ($mediaItem) {
            $mediaItem->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Mídia não encontrada.'], 404);
    }

    /**
     * Define uma mídia como principal para um imóvel.
     *
     * @param Request $request
     * @param Property $property
     * @param string $mediaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setMainMedia(Request $request, Property $property, string $mediaId)
    {
        $mediaItem = $property->getMedia('property')->firstWhere('id', $mediaId);

        if (!$mediaItem) {
            return response()->json(['success' => false, 'message' => 'Mídia não encontrada.'], 404);
        }

        $property->getMedia('property')->each(function ($media) {
            $media->setCustomProperty('is_main', false)->save();
        });

        $mediaItem->setCustomProperty('is_main', true)->save();

        return response()->json(['success' => true]);
    }

    /**
     * Reordena as mídias de um imóvel.
     *
     * @param Request $request
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function sortMedia(Request $request, Property $property)
    {
        $order = $request->input('order');
        foreach ($order as $index => $mediaId) {
            $property->media()->find($mediaId)->update(['order_column' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}