<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongEvaluationAnswer extends Model
{
    protected $guarded = [];

    public function response()
    {
        return $this->belongsTo(IbalongEvaluationResponse::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(IbalongEvaluationQuestion::class, 'question_id');
    }

    public function getValueAttribute($value)
    {
        $decoded = json_decode($value, true);
        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
            ? $decoded
            : $value;
    }
}
