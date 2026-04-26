<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoterLog extends Model
{
    protected $guarded = [];

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