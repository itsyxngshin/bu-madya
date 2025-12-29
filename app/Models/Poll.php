<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $guarded = [];

    public function options() {
        return $this->hasMany(PollOption::class);
    }

    public function votes() {
        return $this->hasMany(PollVote::class);
    }
}
