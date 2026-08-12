<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongPoll extends Model
{
    protected $guarded = [];

    protected $casts = [
        'require_ticket' => 'boolean',
        'is_active' => 'boolean',
        'nominee_ids' => 'array', // This is required for the checkbox array to work!
    ];

    public function hackathon()
    {
        return $this->belongsTo(IbalongHackathon::class);
    }

    public function event()
    {
        return $this->belongsTo(IbalongEvent::class);
    }

    public function votes()
    {
        return $this->hasMany(IbalongVote::class, 'poll_id');
    }
}
