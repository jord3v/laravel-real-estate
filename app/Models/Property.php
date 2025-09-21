<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;


class Property extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Filterable;

    protected $fillable = [
        'owner_id', 
        'type_id', // Chave estrangeira para a tabela types
        'code',
        'purpose',
        'address', // Coluna JSON para endereço completo
        'description', 
        'compositions',
        'dimensions',
        'characteristics',
        'business_options',
        'publication',
        'status',
    ];

    protected $casts = [
        'address' => 'array', // Converte a coluna JSON de endereço para um array
        'compositions' => 'array',
        'dimensions' => 'array',
        'characteristics' => 'array', // Novo campo JSON
        'business_options' => 'array',
        'publication' => 'array', // Novo campo JSON
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('property')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->onlyKeepLatest(25);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function owner()
    {
        return $this->belongsTo(Customer::class, 'owner_id');
    }

    /**
     * Relacionamento com a tabela types
     * Um imóvel pertence a um tipo
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }
}