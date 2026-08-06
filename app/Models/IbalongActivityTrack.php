<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongActivityTrack extends Model {
    protected $guarded = [];
    public function activity() { return $this->belongsTo(IbalongActivity::class, 'activity_id'); }
    public function slots() { return $this->hasMany(IbalongActivitySlot::class, 'track_id'); }
    public function mentor() { return $this->belongsTo(IbalongUser::class, 'mentor_id'); } // Assuming IbalongUser maps to 'users'
}
