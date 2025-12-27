<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'email', 'college_id', 'proponent_type', 'status',
        'title', 'project_type', 'rationale', 'potential_partners',
        'modality', 'venue', 'target_start_date', 'target_end_date', 'target_beneficiaries',
        'estimated_budget', 'budget_description', 'admin_remarks'
    ];

    protected $casts = [
        'target_start_date' => 'date',
        'target_end_date'   => 'date',
        'estimated_budget'  => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class); // Assuming you have this model
    }

    public function objectives()
    {
        return $this->hasMany(ProposalObjective::class);
    }
}