<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EventAnswer extends Model {
    protected $fillable = ['question_id', 'registration_id', 'answer'];

    public function registration() {
        return $this->belongsTo(EventRegistration::class, 'registration_id');
    }
    public function question() {
        return $this->belongsTo(EventQuestion::class, 'question_id');
    }
}
