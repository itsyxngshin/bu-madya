<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongEvaluationQuestion extends Model
{
    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function evaluation()
    {
        return $this->belongsTo(IbalongEvaluation::class, 'evaluation_id');
    }

    public function answers()
    {
        return $this->hasMany(IbalongEvaluationAnswer::class, 'question_id');
    }
}
