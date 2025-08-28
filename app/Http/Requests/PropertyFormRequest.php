<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyFormRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Ajuste conforme sua lógica de autorização
    }

    /**
     * Regras de validação para o imóvel.
     *
     * @return array
     */
    public function rules()
    {
        $propertyId = $this->route('property') ? $this->route('property')->id : null;

        return [
            "owner_id" => "required|exists:customers,id",
            "code" => [
                "required",
                "string",
                Rule::unique('properties', 'code')->ignore($propertyId),
            ],
            "type" => "required|string",
            "purpose" => "required|string",

            // Validação para o campo JSON de endereço
            "address.cep" => "required|string|size:8",
            "address.street" => "required|string|max:255",
            "address.city" => "required|string|max:255",
            "address.state" => "required|string|max:255",

            // Validação para os campos JSON aninhados
            "compositions.bedrooms" => "nullable|integer",
            "compositions.suites" => "nullable|integer",
            "compositions.bathrooms" => "nullable|integer",
            "compositions.living_rooms" => "nullable|integer",
            "compositions.parking_spots" => "nullable|integer",
            "dimensions.usable_area" => "nullable|string",
            "dimensions.total_area" => "nullable|string",

            "characteristics" => "nullable|array",

            // Regras para as opções de negócio
            "business_options.sale.price" => "nullable|numeric|min:0",
            "business_options.sale.show_price" => "nullable",
            "business_options.sale.financing" => "nullable",
            "business_options.sale.trade_in" => "nullable",
            "business_options.rental.price" => "nullable|numeric|min:0",
            "business_options.rental.show_price" => "nullable",
            "business_options.rental.deposit" => "nullable",
            "business_options.season.price" => "nullable|numeric|min:0",
            "business_options.season.show_price" => "nullable",
            "business_options.season.period" => "nullable|string|in:daily,weekly,monthly",
            "business_options.season.start_date" => "nullable|date",
            "business_options.season.end_date" => "nullable|date",

            "description" => "nullable|string",

            // Regras para a nova etapa de publicação
            "publication.portals" => "nullable|array",
            "publication.my_site" => "nullable",
            "publication.period_type" => "nullable|string|in:manual,range",
            "publication.start_date" => "nullable|date",
            "publication.end_date" => "nullable|date",

            "status" => "required|in:draft,published",
        ];
    }

    /**
     * Prepara os dados para validação, tratando campos booleanos.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'business_options' => array_merge($this->input('business_options', []), [
                'sale' => array_merge($this->input('business_options.sale', []), [
                    'show_price' => $this->has('business_options.sale.show_price'),
                    'financing' => $this->has('business_options.sale.financing'),
                    'trade_in' => $this->has('business_options.sale.trade_in'),
                ]),
                'rental' => array_merge($this->input('business_options.rental', []), [
                    'show_price' => $this->has('business_options.rental.show_price'),
                    'deposit' => $this->has('business_options.rental.deposit'),
                ]),
                'season' => array_merge($this->input('business_options.season', []), [
                    'show_price' => $this->has('business_options.season.show_price'),
                ]),
            ]),
            'publication' => array_merge($this->input('publication', []), [
                'my_site' => $this->has('publication.my_site'),
            ]),
        ]);

        // Remove datas de publicação se period_type não for 'range'
        if ($this->input('publication.period_type') !== 'range') {
            $this->merge([
                'publication' => array_merge($this->input('publication', []), [
                    'start_date' => null,
                    'end_date' => null,
                ]),
            ]);
        }
    }
}