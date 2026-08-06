<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongAppointment extends Model {
    protected $guarded = [];
    public function slot() { return $this->belongsTo(IbalongActivitySlot::class, 'slot_id'); }
    public function team() { return $this->belongsTo(IbalongRegistration::class, 'team_id'); }
}
