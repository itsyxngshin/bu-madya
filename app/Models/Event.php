<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'cover_image',
        'registration_link', 'registration_button_text',
        'start_date', 'end_date', 'is_active', 'capacity',
        'location', 'is_internal_rsvp', 'classification', 'college_id', 'user_id',
        'program', 'year_level','checkin_start','checkin_end', 
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'checkin_start' => 'datetime',
        'checkin_end' => 'datetime',
        'is_active' => 'boolean',
        'is_internal_rsvp' => 'boolean',
    ];

    // Add the relationship
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper to check if event is currently open
    public function isOpen()
    {
        if (!$this->is_active) return false;
        // If no end date is set, assume it's open indefinitely
        if (!$this->end_date) return true;
        return now()->lte($this->end_date);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // [NEW] The users who have been granted shared access
    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'event_collaborators', 'event_id', 'user_id');
    }
}
