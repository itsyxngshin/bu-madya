<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IbalongQuest extends Model
{
    protected $fillable = [
        'title', 'description', 'deadline', 'is_published'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function tasks(): HasMany
    {
        // Tasks are automatically ordered by the index you set in the builder
        return $this->hasMany(IbalongQuestTask::class, 'quest_id')->orderBy('order_index');
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(IbalongQuestCriteria::class, 'quest_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(IbalongQuestSubmission::class, 'quest_id');
    }
}
