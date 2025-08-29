<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'property_id',
        'lessor_id',
        'lessee_id',
        'guarantor_id',

        'contract_type',
        'term_months',
        'start_date',
        'end_date',
        'rent_amount',
        'due_day',
        'payment_place',
        'readjustment_index',
        'alternative_indexes',
        'late_payment_fine_percent',
        'late_payment_fine_limit',
        'late_payment_interest',
        'monetary_correction',
        'additional_charges',
        'use_destination',
        'delivery_conditions',
        'prohibitions',
        'maintenance_obligations',
        'benfeitorias',
        'guarantee_type',
        'attorney_fees_percent',
        'elected_forum',
        'via_count',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'alternative_indexes' => 'array',
        'additional_charges' => 'array',
        'prohibitions' => 'array',
    ];

    /**
     * Define the relationships for the model.
     */

    // Um contrato pertence a um imóvel.
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Um contrato pertence a um locador (que é um Customer).
    public function lessor()
    {
        return $this->belongsTo(Customer::class, 'lessor_id');
    }

    // Um contrato pertence a um locatário (que é um Customer).
    public function lessee()
    {
        return $this->belongsTo(Customer::class, 'lessee_id');
    }

    // Um contrato pode ter um fiador (que é um Customer).
    public function guarantor()
    {
        return $this->belongsTo(Customer::class, 'guarantor_id');
    }

    // Um contrato pode ter várias garantias.
    public function guarantees()
    {
        return $this->hasMany(Guarantee::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}