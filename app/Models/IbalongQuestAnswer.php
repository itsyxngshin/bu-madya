<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IbalongQuestAnswer extends Model
{
    protected $fillable = [
        'submission_id', 'task_id', 'answer_text', 'file_path'
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(IbalongQuestSubmission::class, 'submission_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(IbalongQuestTask::class, 'task_id');
    }
}
