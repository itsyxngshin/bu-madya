<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingAttendee extends Model
{
    protected $fillable = ['meeting_id', 'student_id', 'name', 'time_in'];
    protected $casts = ['time_in' => 'datetime'];
}
