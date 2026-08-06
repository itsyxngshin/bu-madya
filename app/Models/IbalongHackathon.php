<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongHackathon extends Model {
    protected $guarded = [];
    public function activities() { return $this->hasMany(IbalongActivity::class, 'hackathon_id'); }
}
