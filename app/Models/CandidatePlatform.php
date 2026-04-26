<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatePlatform extends Model
{
    protected $fillable = ['candidate_id', 'title', 'description'];

    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }
}