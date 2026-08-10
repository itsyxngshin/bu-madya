<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongAward extends Model
{
    protected $guarded = [];

    public function hackathon()
    {
        return $this->belongsTo(IbalongHackathon::class, 'hackathon_id');
    }

    public function team()
    {
        return $this->belongsTo(IbalongRegistration::class, 'team_id');
    }
}
