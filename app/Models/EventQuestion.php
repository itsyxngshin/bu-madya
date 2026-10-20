<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EventQuestion extends Model {
    protected $fillable = ['question_text', 'type', 'is_required'];

    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }
    public function answers() {
        return $this->hasMany(EventAnswer::class, 'question_id');
    }
}
