<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'user_id',
        'academic_year_id',
        'title',
        'meeting_date',
        'start_time',
        'location',
        'agenda',
        'minutes',
        'status'
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'start_time' => 'datetime'
    ];

    // 1. Relationship to the User/Organization
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. THIS IS THE MISSING METHOD CAUSING THE ERROR
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // 3. Relationship to the Attendees
    public function attendees()
    {
        return $this->hasMany(MeetingAttendee::class);
    }
}
