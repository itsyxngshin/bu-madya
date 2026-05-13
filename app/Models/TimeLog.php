<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    use HasFactory;

    // 1. Allow Mass Assignment
    protected $fillable = [
        'user_id',
        'log_date',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
        'total_minutes_rendered',
        'is_overtime_approved',
        'status',
    ];

    // 2. Cast to Carbon Datatypes
    protected $casts = [
        'log_date' => 'date',
        'morning_in' => 'datetime',
        'morning_out' => 'datetime',
        'afternoon_in' => 'datetime',
        'afternoon_out' => 'datetime',
        'total_minutes_rendered' => 'integer',
        'is_overtime_approved' => 'boolean',
    ];

    // 3. Setup the Inverse Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
