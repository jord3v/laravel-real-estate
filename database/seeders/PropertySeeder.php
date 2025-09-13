<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Apartamento', 'Casa', 'Terreno'];
        $purposes = ['Venda', 'Aluguel', 'Temporada'];
        $cities = ['São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Curitiba', 'Florianópolis', 'Porto Alegre', 'Salvador', 'Recife', 'Fortaleza', 'Brasília'];
        $states = ['SP', 'RJ', 'MG', 'PR', 'SC', 'RS', 'BA', 'PE', 'CE', 'DF'];
        $characteristicsList = ['Piscina', 'Churrasqueira', 'Varanda', 'Elevador', 'Portaria 24h', 'Academia', 'Playground', 'Salão de Festas', 'Jardim', 'Vista para o mar'];
        $businessOptionsList = ['Venda', 'Aluguel', 'Temporada'];
        $statusList = ['disponivel', 'indisponivel', 'reservado'];

        for ($i = 1; $i <= 50; $i++) {
            $type = $types[array_rand($types)];
            $purpose = $purposes[array_rand($purposes)];
            $cityIndex = array_rand($cities);
            $city = $cities[$cityIndex];
            $state = $states[$cityIndex];
            $bedrooms = $type === 'Terreno' ? 0 : rand(1, 5);
            $bathrooms = $type === 'Terreno' ? 0 : rand(1, 4);
            $parking = $type === 'Terreno' ? 0 : rand(0, 3);
            $area = $type === 'Terreno' ? rand(200, 2000) : rand(40, 400);
            $characteristics = array_rand(array_flip($characteristicsList), rand(2, 5));
            $status = $statusList[array_rand($statusList)];
            $address = [
                'cep' => str_pad(rand(10000000,99999999),8,'0',STR_PAD_LEFT),
                'street' => 'Rua ' . chr(65 + $i) . ' Nº ' . rand(10, 999),
                'city' => $city,
                'state' => $state,
                'neighborhood' => 'Bairro ' . chr(65 + $i),
            ];
            $compositions = [
                'bedrooms' => $bedrooms,
                'suites' => $type === 'Terreno' ? 0 : rand(0, 2),
                'bathrooms' => $bathrooms,
                'living_rooms' => $type === 'Terreno' ? 0 : rand(1, 2),
                'car_spaces' => $parking,
            ];
            $dimensions = [
                'usable_area' => $area,
                'total_area' => $area + rand(0, 100),
            ];
            $businessOptions = [
                'sale' => [
                    'price' => $purpose === 'Venda' ? rand(100000, 5000000) : null,
                    'show_price' => (bool)rand(0,1),
                    'financing' => (bool)rand(0,1),
                    'trade_in' => (bool)rand(0,1),
                ],
                'rental' => [
                    'price' => $purpose === 'Aluguel' ? rand(800, 10000) : null,
                    'show_price' => (bool)rand(0,1),
                    'deposit' => (bool)rand(0,1),
                ],
                'season' => [
                    'price' => $purpose === 'Temporada' ? rand(200, 3000) : null,
                    'show_price' => (bool)rand(0,1),
                    'period' => $purpose === 'Temporada' ? (['daily','weekly','monthly'])[rand(0,2)] : null,
                    'start_date' => $purpose === 'Temporada' ? now()->addDays(rand(1,30))->format('Y-m-d') : null,
                    'end_date' => $purpose === 'Temporada' ? now()->addDays(rand(31,90))->format('Y-m-d') : null,
                ],
            ];
            $publication = [
                'portals' => ['Zap', 'VivaReal'],
                'my_site' => (bool)rand(0,1),
                'period_type' => (['manual','range'])[rand(0,1)],
                'start_date' => now()->addDays(rand(1,30))->format('Y-m-d'),
                'end_date' => now()->addDays(rand(31,90))->format('Y-m-d'),
            ];
            Property::create([
                'code' => strtoupper(substr($type, 0, 2)) . str_pad($i, 3, '0', STR_PAD_LEFT),
                'type' => $type,
                'purpose' => $purpose,
                'address' => $address,
                'description' => 'Imóvel gerado automaticamente para testes.',
                'compositions' => $compositions,
                'dimensions' => $dimensions,
                'characteristics' => $characteristics,
                'business_options' => $businessOptions,
                'publication' => $publication,
                'status' => 'draft',
            ]);
        }
    }
}
