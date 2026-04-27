<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $guarded = [];

    // THE FIX: This is the exact relationship Laravel was looking for!
    public function electionPosition()
    {
        return $this->belongsTo(ElectionPosition::class);
    }

    // You will also need these relationships since the profile page loads them
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

    // The relationship we added earlier for the Live Analytics
    public function votes() 
    {
        return $this->hasMany(Vote::class);
    }
}