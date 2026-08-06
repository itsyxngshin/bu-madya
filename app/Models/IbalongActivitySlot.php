<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongActivitySlot extends Model {
    protected $guarded = [];
    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];
    public function track() { return $this->belongsTo(IbalongActivityTrack::class, 'track_id'); }
    public function appointments() { return $this->hasMany(IbalongAppointment::class, 'slot_id'); }
}
