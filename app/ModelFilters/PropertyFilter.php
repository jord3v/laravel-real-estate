<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class PropertyFilter extends ModelFilter
{

    // Filtro por múltiplos IDs
    public function ids($value)
    {
        $ids = is_array($value) ? $value : explode(',', $value);
        return $this->whereIn('id', $ids);
    }
    public $relations = [];

    // Filtro por tipo de imóvel
    public function type($value)
    {
        $values = is_array($value) ? $value : [$value];
        return $this->whereIn('type', $values);
    }

    // Filtro por finalidade
    public function purpose($value)
    {
        $values = is_array($value) ? $value : [$value];
        return $this->whereIn('purpose', $values);
    }

    // Filtro genérico para campos de compositions
    public function compositionsField($field, $values)
    {
        $values = is_array($values) ? $values : [$values];
        $values = array_map('intval', $values);
        return $this->where(function ($q) use ($field, $values) {
            foreach ($values as $value) {
                if ($value === 3) {
                    $q->orWhereRaw("JSON_EXTRACT(compositions, '$.$field') >= ?", [$value]);
                } else {
                    $q->orWhereJsonContains("compositions->$field", $value);
                }
            }
        });
    }

    // Filtro por quartos
    public function bedrooms($values)
    {
        return $this->compositionsField('bedrooms', $values);
    }

    // Filtro por banheiros
    public function bathrooms($values)
    {
        return $this->compositionsField('bathrooms', $values);
    }

    // Filtro por vagas de garagem
    public function parking($values)
    {
        return $this->compositionsField('car_spaces', $values);
    }


    // Filtro por faixa de preço usando minPrice e maxPrice
    public function minPrice($min)
    {
        $min = is_array($min) ? (int)$min[0] : (int)$min;
    $max = request()->input('maxPrice');
    $max = $max !== null ? (is_array($max) ? (int)$max[0] : (int)$max) : 0;
        return $this->where(function ($q) use ($min, $max) {
            foreach (["sale", "rental", "season"] as $opt) {
                if ($max > 0) {
                    $q->orWhereRaw("JSON_EXTRACT(business_options, '$.$opt.price') BETWEEN ? AND ?", [$min, $max]);
                } else {
                    $q->orWhereRaw("JSON_EXTRACT(business_options, '$.$opt.price') >= ?", [$min]);
                }
            }
        });
    }

    public function maxPrice($max)
    {
        // Não faz nada, pois o filtro é aplicado junto com minPrice
        return $this;
    }

    // Filtro por localização (cidade, estado ou código)
    public function location($value)
    {
        $values = is_array($value) ? $value : [$value];
        return $this->where(function ($q) use ($values) {
            foreach ($values as $val) {
                $val = strtolower($val);
                $q->orWhereRaw("LOWER(JSON_EXTRACT(address, '$.city')) LIKE ?", ["%$val%"])
                  ->orWhereRaw("LOWER(JSON_EXTRACT(address, '$.state')) LIKE ?", ["%$val%"])
                  ->orWhereRaw("LOWER(code) LIKE ?", ["%$val%"]);
            }
        });
    }
}