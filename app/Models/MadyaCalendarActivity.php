<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MadyaCalendarActivity extends Model
{
    protected $fillable = [
        'title', 'start_date', 'end_date', 'category',
        'organizer', 'external_link', 'description', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
}
