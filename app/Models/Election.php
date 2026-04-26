<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $fillable = [
        'user_id', 'academic_year_id', 'title', 'description', 'cover_photo_path', 
        'type', 'status', 'allow_guest_voting', 'slug', 
        'application_start', 'application_end', 
        'voting_start', 'voting_end', 'results_release'
    ];

    protected $casts = [
        'allow_guest_voting' => 'boolean',
        'application_start' => 'datetime',
        'application_end' => 'datetime',
        'voting_start' => 'datetime',
        'voting_end' => 'datetime',
        'results_release' => 'datetime',
    ];

    // The Admin/Organization that created this election
    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function academicYear() {
        return $this->belongsTo(AcademicYear::class);
    }

    public function positions() {
        return $this->hasMany(ElectionPosition::class)->orderBy('order');
    }

    public function candidates() {
        return $this->hasMany(Candidate::class);
    }

    public function voterLogs() {
        return $this->hasMany(VoterLog::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }
}