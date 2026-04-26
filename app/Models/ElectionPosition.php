<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionPosition extends Model
{
    protected $fillable = [
        'election_id', 'title', 'max_winners', 'order'
    ];

    public function election() {
        return $this->belongsTo(Election::class);
    }

    public function candidates() {
        return $this->hasMany(Candidate::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }
}