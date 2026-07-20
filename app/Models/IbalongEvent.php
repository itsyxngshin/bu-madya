<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongEvent extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'type',
        'venue_or_link', 'start_datetime', 'end_datetime',
        'max_capacity', 'is_active', 'allow_self_checkin'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function registrations()
    {
        return $this->hasMany(IbalongEventRegistration::class, 'event_id');
    }
}
