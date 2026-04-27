<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $guarded = [];

    // Used by the Public Profile
    public function electionPosition()
    {
        return $this->belongsTo(ElectionPosition::class);
    }

    // Used by the Admin Dashboard
    public function position()
    {
        return $this->belongsTo(ElectionPosition::class, 'election_position_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function platforms()
    {
        return $this->hasMany(CandidatePlatform::class);
    }

    public function credentials()
    {
        return $this->hasMany(CandidateCredential::class);
    }

    public function votes() 
    {
        return $this->hasMany(Vote::class);
    }
}