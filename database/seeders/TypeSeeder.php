<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Apartamento',
                'description' => 'Unidade habitacional em edificio residencial',
                'active' => true,
            ],
            [
                'name' => 'Apartamento de luxo',
                'description' => 'Apartamento com acabamentos e localizacao premium',
                'active' => false,
            ],
            [
                'name' => 'Area',
                'description' => 'Terreno ou espaco destinado a diferentes usos',
                'active' => false,
            ],
            [
                'name' => 'Armazem',
                'description' => 'Espaco para armazenamento de mercadorias',
                'active' => false,
            ],
            [
                'name' => 'Barracao',
                'description' => 'Construcao ampla para uso industrial ou comercial',
                'active' => false,
            ],
            [
                'name' => 'Casa',
                'description' => 'Residencia unifamiliar terrea ou com pavimentos',
                'active' => true,
            ],
            [
                'name' => 'Casa de condominio',
                'description' => 'Casa localizada em condominio fechado',
                'active' => true,
            ],
            [
                'name' => 'Casa de vila',
                'description' => 'Casa em conjunto residencial com caracteristicas especificas',
                'active' => false,
            ],
            [
                'name' => 'Casa geminada',
                'description' => 'Casa que compartilha parede lateral com outra unidade',
                'active' => false,
            ],
            [
                'name' => 'Chacara',
                'description' => 'Propriedade rural de pequeno porte para lazer',
                'active' => false,
            ],
            [
                'name' => 'Chale',
                'description' => 'Casa de campo com arquitetura especifica',
                'active' => false,
            ],
            [
                'name' => 'Cobertura',
                'description' => 'Apartamento no ultimo andar com area externa',
                'active' => true,
            ],
            [
                'name' => 'Conjunto Comercial',
                'description' => 'Unidades comerciais agrupadas em um mesmo edificio',
                'active' => false,
            ],
            [
                'name' => 'Deposito',
                'description' => 'Espaco destinado ao armazenamento',
                'active' => false,
            ],
            [
                'name' => 'Duplex',
                'description' => 'Apartamento ou casa com dois pavimentos',
                'active' => false,
            ],
            [
                'name' => 'Escritorio',
                'description' => 'Espaco comercial para atividades administrativas',
                'active' => false,
            ],
            [
                'name' => 'Fabrica',
                'description' => 'Instalacao industrial para producao',
                'active' => false,
            ],
            [
                'name' => 'Fazenda',
                'description' => 'Propriedade rural de grande extensao',
                'active' => false,
            ],
            [
                'name' => 'Flat',
                'description' => 'Apart-hotel com servicos inclusos',
                'active' => false,
            ],
            [
                'name' => 'Galpao',
                'description' => 'Construcao ampla para uso industrial ou logistico',
                'active' => false,
            ],
            [
                'name' => 'Garagem',
                'description' => 'Espaco para estacionamento de veiculos',
                'active' => false,
            ],
            [
                'name' => 'Hotel',
                'description' => 'Estabelecimento comercial de hospedagem',
                'active' => false,
            ],
            [
                'name' => 'Industria',
                'description' => 'Complexo industrial para producao em larga escala',
                'active' => false,
            ],
            [
                'name' => 'Kitnet',
                'description' => 'Apartamento compacto com ambiente integrado',
                'active' => true,
            ],
            [
                'name' => 'Loft',
                'description' => 'Espaco amplo com conceito aberto',
                'active' => false,
            ],
            [
                'name' => 'Loja',
                'description' => 'Espaco comercial para venda de produtos',
                'active' => true,
            ],
            [
                'name' => 'Lote',
                'description' => 'Terreno urbano para construcao',
                'active' => true,
            ],
            [
                'name' => 'Pavilhao',
                'description' => 'Construcao ampla para eventos ou exposicoes',
                'active' => false,
            ],
            [
                'name' => 'Ponto Comercial',
                'description' => 'Espaco comercial estrategicamente localizado',
                'active' => false,
            ],
            [
                'name' => 'Pousada',
                'description' => 'Estabelecimento de hospedagem de pequeno porte',
                'active' => false,
            ],
            [
                'name' => 'Predio comercial',
                'description' => 'Edificio destinado a atividades comerciais',
                'active' => false,
            ],
            [
                'name' => 'Sala comercial',
                'description' => 'Unidade comercial em edificio corporativo',
                'active' => true,
            ],
            [
                'name' => 'Salao',
                'description' => 'Espaco amplo para eventos ou atividades comerciais',
                'active' => false,
            ],
            [
                'name' => 'Sitio',
                'description' => 'Propriedade rural de medio porte',
                'active' => false,
            ],
            [
                'name' => 'Sobrado',
                'description' => 'Casa de dois ou mais pavimentos',
                'active' => true,
            ],
            [
                'name' => 'Sobrado em condominio',
                'description' => 'Sobrado localizado em condominio fechado',
                'active' => false,
            ],
            [
                'name' => 'Studio',
                'description' => 'Apartamento compacto com ambiente unico',
                'active' => false,
            ],
            [
                'name' => 'Terreno',
                'description' => 'Area de terra para construcao ou investimento',
                'active' => true,
            ],
            [
                'name' => 'Triplex',
                'description' => 'Apartamento ou casa com tres pavimentos',
                'active' => false,
            ],
        ];

        foreach ($types as $type) {
            Type::create($type);
        }
    }
}