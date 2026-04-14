<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function signatures()
    {
        return $this->hasMany(CampaignSignature::class);
    }
}
