<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'user_id',
        'project_id'
        // 'project_id' // Uncomment if linking responses to specific projects
    ];

    /**
     * Get the evaluation form this response belongs to.
     */
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    /**
     * Get the user who submitted this response.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the individual answers for this response.
     */
    public function answers()
    {
        return $this->hasMany(EvaluationAnswer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}