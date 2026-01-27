<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_response_id',
        'evaluation_question_id',
        'answer_value',
    ];

    /**
     * Get the parent response header.
     */
    public function response()
    {
        return $this->belongsTo(EvaluationResponse::class, 'evaluation_response_id');
    }

    /**
     * Get the specific question this answer belongs to.
     */
    public function question()
    {
        return $this->belongsTo(EvaluationQuestion::class, 'evaluation_question_id');
    }
    
    /**
     * Optional: Helper to check if the answer is a JSON array (for checkboxes)
     */
    public function getValueAttribute($value)
    {
        // Try to decode JSON if it looks like an array, otherwise return string
        $decoded = json_decode($value, true);
        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) 
            ? $decoded 
            : $value;
    }
}