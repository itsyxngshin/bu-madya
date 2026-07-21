<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MadyaCalendarActivity extends Model
{
    protected $fillable = [
        'title',
        'activity_date',
        'category',
        'organizer',
        'external_link',
        'description',
        'is_active'
    ];

    protected $casts = [
        'activity_date' => 'date',
        'is_active' => 'boolean',
    ];
}
