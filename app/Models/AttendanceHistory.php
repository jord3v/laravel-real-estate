<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceHistory extends Model
{
    use HasFactory;
    
    protected $table = 'attendance_history';

    protected $fillable = [
        'attendance_id',
        'old_status',
        'new_status',
        'old_notes',
        'new_notes',
    ];
}