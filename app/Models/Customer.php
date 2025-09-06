<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'type',
        'document_type',
        'cpf',
        'cnpj',
        'rg',
        'marital_status',
        'nationality',
        'profession',
        'spouse_name',
        'spouse_rg',
        'spouse_cpf',
        'spouse_profession',
    ];

    // Uma pessoa pode ser proprietária de muitos imóveis
    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    // Uma pessoa pode ter muitos contratos como proprietário
    public function ownedLeases()
    {
        return $this->hasMany(Lease::class, 'owner_id');
    }
    
    // Uma pessoa pode ter muitos contratos como inquilino
    public function rentedLeases()
    {
        return $this->hasMany(Lease::class, 'renter_id');
    }
}
