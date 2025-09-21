<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class FavoriteController extends Controller
{
    private const COOKIE_NAME = 'favorites';
    private const COOKIE_LIFETIME = 60 * 24 * 30; // 30 dias

    /**
     * Adiciona ou remove um imóvel dos favoritos.
     */
    public function toggle(Request $request): JsonResponse
    {
        try {
            $propertyId = $this->validatePropertyId($request->input('property_id'));
            $favorites = $this->getFavorites($request);
            
            $favorites = $this->toggleFavorite($favorites, $propertyId);
            
            return response()->json(['favorites' => $favorites])
                ->cookie(self::COOKIE_NAME, json_encode($favorites), self::COOKIE_LIFETIME);
                
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao processar favorito.'
            ], 400);
        }
    }

    /**
     * Retorna a lista de imóveis favoritos com informações detalhadas.
     */
    public function properties(Request $request): JsonResponse
    {
        try {
            $favorites = $this->getFavorites($request);
            
            if (empty($favorites)) {
                return response()->json(['data' => []]);
            }
            
            $properties = $this->getPropertiesWithDetails($favorites);
            
            return response()->json(['data' => $properties]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao carregar favoritos.'
            ], 500);
        }
    }

    /**
     * Valida e converte o ID da propriedade.
     */
    private function validatePropertyId(mixed $propertyId): int
    {
        $id = (int) $propertyId;
        
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID da propriedade inválido.');
        }
        
        return $id;
    }

    /**
     * Obtém a lista de favoritos do cookie.
     */
    private function getFavorites(Request $request): array
    {
        $favorites = json_decode($request->cookie(self::COOKIE_NAME, '[]'), true);
        
        return is_array($favorites) ? array_unique(array_filter(array_map('intval', $favorites))) : [];
    }

    /**
     * Adiciona ou remove um favorito da lista.
     */
    private function toggleFavorite(array $favorites, int $propertyId): array
    {
        if (in_array($propertyId, $favorites)) {
            return array_values(array_diff($favorites, [$propertyId]));
        }
        
        $favorites[] = $propertyId;
        return $favorites;
    }

    /**
     * Busca propriedades com informações detalhadas.
     */
    private function getPropertiesWithDetails(array $favoriteIds): array
    {
        $properties = Property::whereIn('id', $favoriteIds)
            ->with(['media', 'type'])
            ->get();

        return $properties->map(function (Property $property) {
            return $this->formatPropertyData($property);
        })->toArray();
    }

    /**
     * Formata os dados da propriedade para o frontend.
     */
    private function formatPropertyData(Property $property): array
    {
        $images = $property->getMedia('property')
            ->map(fn($media) => $media->getUrl('preview'))
            ->toArray();

        $price = $this->extractPrice($property->business_options ?? []);
        
        return [
            'id' => $property->id,
            'title' => $property->type?->name ?? 'Tipo não definido',
            'code' => $property->code,
            'purpose' => $property->purpose,
            'price' => $price,
            'location' => $this->formatLocation($property->address ?? []),
            'description' => $property->description,
            'bedrooms' => $property->compositions['bedrooms'] ?? 0,
            'bathrooms' => $property->compositions['bathrooms'] ?? 0,
            'parking' => $property->compositions['parking'] ?? 0,
            'area' => $property->dimensions['area'] ?? 0,
            'images' => $images,
            'type' => $property->type,
        ];
    }

    /**
     * Extrai o preço das opções de negócio.
     */
    private function extractPrice(array $businessOptions): ?float
    {
        return $businessOptions['sale']['price'] ?? 
               $businessOptions['rental']['price'] ?? 
               $businessOptions['season']['price'] ?? 
               null;
    }

    /**
     * Formata a localização da propriedade.
     */
    private function formatLocation(array $address): string
    {
        $city = $address['city'] ?? '';
        $state = $address['state'] ?? '';
        
        if ($city && $state) {
            return "{$city}, {$state}";
        }
        
        return $city ?: $state;
    }
}
