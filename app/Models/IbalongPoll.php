<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongPoll extends Model
{
    protected $guarded = [];

    public function hackathon()
    {
        return $this->belongsTo(IbalongHackathon::class, 'hackathon_id');
    }

    public function votes()
    {
        return $this->hasMany(IbalongVote::class, 'poll_id');
    }

    public function event()
    {
        return $this->belongsTo(IbalongEvent::class, 'event_id');
    }
}
