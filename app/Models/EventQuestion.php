<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EventQuestion extends Model {
    protected $guarded = [];

    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }
    public function answers() {
        return $this->hasMany(EventAnswer::class, 'question_id');
    }
}
