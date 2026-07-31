<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IbalongQuestTask extends Model
{
    protected $fillable = [
        'quest_id', 'question', 'type', 'options', 'max_file_size_mb', 'is_required', 'order_index'
    ];

    protected $casts = [
        'options' => 'array', // Automatically handles the JSON for dropdown/checkbox items
        'is_required' => 'boolean',
    ];

    public function quest(): BelongsTo
    {
        return $this->belongsTo(IbalongQuest::class, 'quest_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(IbalongQuestAnswer::class, 'task_id');
    }
}
