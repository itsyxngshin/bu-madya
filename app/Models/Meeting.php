<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = ['user_id', 'academic_year_id', 'title', 'meeting_date', 'start_time', 'location', 'agenda', 'minutes', 'status'];
    protected $casts = ['meeting_date' => 'date', 'start_time' => 'datetime'];

    public function attendees() { return $this->hasMany(MeetingAttendee::class); }
}
