<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'type',
        'question_text',
        'options',
        'description',
        'is_required',
        'order'
    ];

    /**
     * The attributes that should be cast.
     * This automatically converts the JSON column in DB to a PHP array.
     */
    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function answers()
    {
        return $this->hasMany(EvaluationAnswer::class);
    }
}
