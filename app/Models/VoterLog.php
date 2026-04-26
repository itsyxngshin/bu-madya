<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoterLog extends Model
{
    protected $fillable = [
        'user_id', 'election_id', 
        'guest_email', 'guest_name', 'college_id', 'program', 'year_level', 
        'voted_at'
    ];

    protected $casts = [
        'voted_at' => 'datetime',
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
}