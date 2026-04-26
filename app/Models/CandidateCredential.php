<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateCredential extends Model
{
    protected $fillable = ['candidate_id', 'type', 'description'];

    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }
}