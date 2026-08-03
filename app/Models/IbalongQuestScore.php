<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IbalongQuestScore extends Model
{
    protected $fillable = [
        'submission_id', 'judge_id', 'criteria_id', 'score', 'feedback'
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(IbalongQuestSubmission::class, 'submission_id');
    }

    public function judge(): BelongsTo
    {
        // Links to whoever is logged in and evaluating (User model)
        return $this->belongsTo(IbalongUser::class, 'judge_id');
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(IbalongQuestCriteria::class, 'criteria_id');
    }
}
