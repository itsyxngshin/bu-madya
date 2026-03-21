<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'admin_notes',
        'assigned_org_id',
        'phone_number',
        'year_and_block',
        'nature_of_incident',
        'incident_details',
        'file_upload_path',
        'status',
    ];

    /**
     * The "booted" method of the model.
     * This automatically runs every time a new Incident Report is created.
     */
    protected static function booted()
    {
        static::creating(function ($report) {
            // If it doesn't have a case number yet, generate one!
            if (empty($report->case_number)) {
                // Get the ID of the last report to determine the next number
                $lastReport = self::orderBy('id', 'desc')->first();
                $nextId = $lastReport ? $lastReport->id + 1 : 1;

                // Formats it exactly like your mockup: CASE-0001, CASE-0002
                $report->case_number = 'CASE-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function assignedOrganization()
    {
        return $this->belongsTo(User::class, 'assigned_org_id');
    }
}
