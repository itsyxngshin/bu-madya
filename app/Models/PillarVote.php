<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PillarVote extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function pillar() {
        return $this->belongsTo(Pillar::class);
    }
}
