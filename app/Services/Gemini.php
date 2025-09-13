<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Gemini
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function generateDescription(array $propertyData): ?string
    {
        $prompt = $this->buildPrompt($propertyData);

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            $result = $response->json();
            return $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

        } catch (\Exception $e) {
            Log::error('Erro ao chamar a API do Gemini: ' . $e->getMessage());
            return null;
        }
    }

    private function buildPrompt(array $propertyData): string
    {
        $prompt = "Crie uma descrição de imóvel atraente e profissional para um site de imobiliária. A descrição deve ter no máximo 3 parágrafos e destacar os seguintes pontos:\n\n";
        
        // Dados básicos e de localização
        $prompt .= "Código do imóvel: {$propertyData['code']}\n";
        $prompt .= "Tipo: {$propertyData['type']}\n";
        $prompt .= "Finalidade: {$propertyData['purpose']}\n";
        $prompt .= "Localização: {$propertyData['address']['neighborhood']}, {$propertyData['address']['city']} - {$propertyData['address']['state']}\n";
        
        // Composições
        if (isset($propertyData['compositions']['bedrooms'])) {
            $prompt .= "Quartos: {$propertyData['compositions']['bedrooms']}\n";
        }
        if (isset($propertyData['compositions']['suites'])) {
            $prompt .= "Suítes: {$propertyData['compositions']['suites']}\n";
        }
        if (isset($propertyData['compositions']['bathrooms'])) {
            $prompt .= "Banheiros: {$propertyData['compositions']['bathrooms']}\n";
        }
        if (isset($propertyData['compositions']['living_rooms'])) {
            $prompt .= "Salas: {$propertyData['compositions']['living_rooms']}\n";
        }
        if (isset($propertyData['compositions']['car_spaces'])) {
            $prompt .= "Vagas: {$propertyData['compositions']['car_spaces']}\n";
        }

        // Dimensões
        if (isset($propertyData['dimensions']['usable_area'])) {
            $prompt .= "Área útil: {$propertyData['dimensions']['usable_area']} m²\n";
        }
        if (isset($propertyData['dimensions']['total_area'])) {
            $prompt .= "Área total: {$propertyData['dimensions']['total_area']} m²\n";
        }

        // Opções de Negócio
        $prompt .= "\nOpções de Negócio:\n";
        
        // Venda
        if (isset($propertyData['business_options']['sale']['price'])) {
            $price = number_format($propertyData['business_options']['sale']['price'], 2, ',', '.');
            $prompt .= "- Venda: R$ {$price}\n";
            $prompt .= "  Aceita Financiamento: " . (isset($propertyData['business_options']['sale']['financing']) ? 'Sim' : 'Não') . "\n";
            $prompt .= "  Aceita Permuta: " . (isset($propertyData['business_options']['sale']['trade_in']) ? 'Sim' : 'Não') . "\n";
        } else {
            $prompt .= "- Venda: Não disponível\n";
        }
        
        // Locação
        if (isset($propertyData['business_options']['rental']['price'])) {
            $price = number_format($propertyData['business_options']['rental']['price'], 2, ',', '.');
            $prompt .= "- Locação: R$ {$price}/mês\n";
            $prompt .= "  Exige Caução: " . (isset($propertyData['business_options']['rental']['deposit']) ? 'Sim' : 'Não') . "\n";
        } else {
            $prompt .= "- Locação: Não disponível\n";
        }
        
        // Temporada
        if (isset($propertyData['business_options']['season']['price'])) {
            $price = number_format($propertyData['business_options']['season']['price'], 2, ',', '.');
            $period = $propertyData['business_options']['season']['period'] ?? 'diária';
            $prompt .= "- Temporada: R$ {$price}/" . ucfirst($period) . "\n";
        } else {
            $prompt .= "- Temporada: Não disponível\n";
        }
        
        return $prompt;
    }
}