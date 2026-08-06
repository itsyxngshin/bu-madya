<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongActivity extends Model {
    protected $guarded = [];
    public function hackathon() { return $this->belongsTo(IbalongHackathon::class, 'hackathon_id'); }
    public function tracks() { return $this->hasMany(IbalongActivityTrack::class, 'activity_id'); }
}
