<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccreditationApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'academic_year_id',
        'application_type',
        'status',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'bankbook_photo_path',
        'cbl_path',
        'recent_fliers_path',
        'accomplishment_report_path',
        'audited_financial_report_path',
        'president_name',
        'president_contact',
        'president_email',
        'president_signature_path',
        'adviser_name',
        'adviser_contact',
        'adviser_email',
        'adviser_signature_path',
        'adviser_approval_status',
        'committee_type',
        'committee_approval_status',
        'admin_remarks'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function officers()
    {
        return $this->hasMany(ApplicationOfficer::class);
    }

    public function members()
    {
        return $this->hasMany(ApplicationMember::class);
    }

    public function activities()
    {
        return $this->hasMany(ApplicationActivity::class);
    }
}