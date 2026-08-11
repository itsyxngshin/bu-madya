<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongVote extends Model
{
    protected $guarded = [];

    public function poll()
    {
        return $this->belongsTo(IbalongPoll::class, 'poll_id');
    }

    public function team()
    {
        return $this->belongsTo(IbalongRegistration::class, 'team_id');
    }
}
