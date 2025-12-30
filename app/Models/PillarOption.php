<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PillarOption extends Model
{
    protected $guarded = [];
    public function votes() {
        return $this->hasMany(PillarVote::class);
    }
}
