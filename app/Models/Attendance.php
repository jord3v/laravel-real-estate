<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'customer_id',
        'status',
        'notes',
        'attended_at',
    ];

    // Relacionamento com o imóvel
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Relacionamento com o Lead/prospect
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Novo relacionamento para o histórico
    public function history()
    {
        return $this->hasMany(AttendanceHistory::class);
    }
}
