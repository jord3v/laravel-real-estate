<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Relacionamento com propriedades
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Scope para tipos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para ordenar por nome
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}