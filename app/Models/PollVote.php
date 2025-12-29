<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    protected $fillable = [
        'poll_id', 
        'poll_option_id', 
        'user_id', 
        'session_id', 
        'ip_address'
    ];

    public function vote() {
        return $this->belongsTo(PollVote::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
