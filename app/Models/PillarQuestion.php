<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PillarQuestion extends Model
{
    protected $guarded = [];
    public function pillar() {
        return $this->belongsTo(Pillar::class);
    }
    public function options() {
        return $this->hasMany(PillarOption::class);
    }
    public function votes() {
        return $this->hasMany(PillarVote::class);
    }
}
