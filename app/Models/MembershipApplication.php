<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipApplication extends Model
{
    protected $guarded = [];
    
    public function membership_wave()
    {
        return $this->belongsTo(MembershipWave::class);
    }
}
