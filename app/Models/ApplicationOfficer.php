<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationOfficer extends Model
{
    use HasFactory;

    protected $fillable = [
        'accreditation_application_id',
        'position',
        'complete_name',
        'course_and_year',
        'college_id',
        'contact_number',
        'email_address',
        'home_address',
    ];

    public function application()
    {
        return $this->belongsTo(AccreditationApplication::class, 'accreditation_application_id');
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}