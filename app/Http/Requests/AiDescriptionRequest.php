<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiDescriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'type' => 'required|string',
            'purpose' => 'required|string',
            'address.cep' => 'nullable|string',
            'address.street' => 'required|string',
            'address.neighborhood' => 'required|string',
            'address.city' => 'required|string',
            'address.state' => 'required|string',
            
            'compositions.bedrooms' => 'nullable|integer',
            'compositions.suites' => 'nullable|integer',
            'compositions.bathrooms' => 'nullable|integer',
            'compositions.living_rooms' => 'nullable|integer',
            'compositions.car_spaces' => 'nullable|integer',
            
            'dimensions.usable_area' => 'nullable|string',
            'dimensions.total_area' => 'nullable|string',

            'business_options' => 'nullable|array',
            'business_options.sale' => 'nullable|array',
            'business_options.sale.price' => 'nullable|numeric',
            'business_options.sale.show_price' => 'nullable',
            'business_options.sale.financing' => 'nullable',
            'business_options.sale.trade_in' => 'nullable',
            'business_options.rental' => 'nullable|array',
            'business_options.rental.price' => 'nullable|numeric',
            'business_options.rental.show_price' => 'nullable',
            'business_options.rental.deposit' => 'nullable',
            'business_options.season' => 'nullable|array',
            'business_options.season.price' => 'nullable|numeric',
            'business_options.season.show_price' => 'nullable',
            'business_options.season.period' => 'nullable|string',
            'business_options.season.available_dates' => 'nullable|string',
        ];
    }
}