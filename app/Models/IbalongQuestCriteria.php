<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IbalongQuestCriteria extends Model
{
    // Override Laravel's default pluralization
    protected $table = 'ibalong_quest_criteria';

    protected $guarded = [];

    protected $casts = [
        'rubric_levels' => 'array', // Automatically casts the tiered descriptions
    ];

    public function quest(): BelongsTo
    {
        return $this->belongsTo(IbalongQuest::class, 'quest_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(IbalongQuestScore::class, 'criteria_id');
    }
}
