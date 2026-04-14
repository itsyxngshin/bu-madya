<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignSignature extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'guest_name',
        'guest_email',
        'affiliation',
        'college_id',
        'program',
        'year_level',
    ];

    public function college()
    {
        return $this->belongsTo(\App\Models\College::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
