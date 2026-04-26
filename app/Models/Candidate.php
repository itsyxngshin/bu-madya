<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'user_id', 'election_id', 'college_id', 'election_position_id', 
        'program', 'year_level', 'address', 'profile_photo_path', 
        'e_signature_path', 'status', 'remarks'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function election() {
        return $this->belongsTo(Election::class);
    }

    public function college() {
        return $this->belongsTo(College::class);
    }

    public function position() {
        return $this->belongsTo(ElectionPosition::class, 'election_position_id');
    }

    public function platforms() {
        return $this->hasMany(CandidatePlatform::class);
    }

    public function credentials() {
        return $this->hasMany(CandidateCredential::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }
}
