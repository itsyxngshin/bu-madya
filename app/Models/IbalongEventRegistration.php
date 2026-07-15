<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IbalongEventRegistration extends Model
{
    protected $fillable = [
        'event_id', 'team_id', 'name', 'email', 'affiliation',
        'role', 'ticket_code', 'status'
    ];

    // Auto-generate the HOI ticket code
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($registration) {
            if (empty($registration->ticket_code)) {
                $registration->ticket_code = 'HOI-' . strtoupper(Str::random(8));
            }
        });
    }

    public function event()
    {
        return $this->belongsTo(IbalongEvent::class, 'event_id');
    }

    public function attendances()
    {
        return $this->hasMany(IbalongEventAttendance::class, 'registration_id');
    }

    // Link back to the Master Hackathon Team
    public function team()
    {
        // Make sure this matches your actual model name for the ibalong_registrations table
        return $this->belongsTo(IbalongRegistration::class, 'team_id');
    }
}
