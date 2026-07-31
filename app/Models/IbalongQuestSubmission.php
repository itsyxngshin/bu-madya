<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IbalongQuestSubmission extends Model
{
    protected $fillable = [
        'quest_id', 'team_id', 'status', 'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function quest(): BelongsTo
    {
        return $this->belongsTo(IbalongQuest::class, 'quest_id');
    }

    public function team(): BelongsTo
    {
        // Links back to your main team registration model
        return $this->belongsTo(IbalongRegistration::class, 'team_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(IbalongQuestAnswer::class, 'submission_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(IbalongQuestScore::class, 'submission_id');
    }
}
