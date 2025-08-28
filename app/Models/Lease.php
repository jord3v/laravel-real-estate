<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'owner_id', 'renter_id', 'start_date', 'end_date', 'rent_amount', 'status'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // O proprietário do contrato é uma Pessoa
    public function owner()
    {
        return $this->belongsTo(Customer::class, 'owner_id');
    }

    // O inquilino do contrato é uma Pessoa
    public function renter()
    {
        return $this->belongsTo(Customer::class, 'renter_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
