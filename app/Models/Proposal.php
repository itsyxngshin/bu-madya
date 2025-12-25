<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_step',
        'status',
        'title',
        'project_type',
        'rationale',
        'venue',
        'target_start_date',
        'target_end_date',
        'target_beneficiaries',
        'estimated_budget',
        'objectives_data',
        'budget_breakdown',
        'target_sdgs',
        'admin_remarks'
    ];

    protected $casts = [
        'target_start_date' => 'date',
        'target_end_date' => 'date',
        // Automatically convert JSON to PHP Array
        'objectives_data' => 'array',
        'budget_breakdown' => 'array',
        'target_sdgs' => 'array',
    ];

    /**
     * The student/leader who created this proposal.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}