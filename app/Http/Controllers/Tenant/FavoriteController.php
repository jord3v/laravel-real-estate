<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    // Adiciona ou remove favorito na sessão/cookie
    public function toggle(Request $request): JsonResponse
    {
        $propertyId = (int) $request->input('property_id');
        $favorites = json_decode($request->cookie('favorites', '[]'), true) ?? [];
        if (in_array($propertyId, $favorites)) {
            $favorites = array_values(array_diff($favorites, [$propertyId]));
        } else {
            $favorites[] = $propertyId;
        }
        return response()->json(['favorites' => $favorites])
            ->cookie('favorites', json_encode($favorites), 60 * 24 * 30); // 30 dias
    }

    // Retorna os imóveis favoritos da sessão/cookie
    public function properties(Request $request): JsonResponse
    {
        $favorites = json_decode($request->cookie('favorites', '[]'), true) ?? [];
        $properties = Property::whereIn('id', $favorites)->with('media')->get();
        $result = $properties->map(function ($property) {
            $images = $property->getMedia('property')->map(fn($media) => $media->getUrl('preview'))->toArray();
            return [
                'id' => $property->id,
                'title' => $property->title ?? $property->description,
                'code' => $property->code,
                'purpose' => $property->purpose,
                'price' => $property->business_options['sale']['price'] ?? $property->business_options['rental']['price'] ?? $property->business_options['season']['price'] ?? null,
                'location' => $property->address['city'] ?? $property->address['state'] ?? '',
                'description' => $property->description,
                'bedrooms' => $property->compositions['bedrooms'] ?? 0,
                'bathrooms' => $property->compositions['bathrooms'] ?? 0,
                'parking' => $property->compositions['parking'] ?? 0,
                'area' => $property->dimensions['area'] ?? 0,
                'imagens' => $images,
                'type' => $property->type,
            ];
        });
        return response()->json(['data' => $result]);
    }
}
