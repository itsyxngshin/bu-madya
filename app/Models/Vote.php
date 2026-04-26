<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = [];
    public function election() {
        return $this->belongsTo(Election::class);
    }

    public function position() {
        return $this->belongsTo(ElectionPosition::class, 'election_position_id');
    }

    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }
}