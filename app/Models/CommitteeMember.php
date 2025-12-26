<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'committee_id', 
        'user_id', // Optional: if linking to a registered user
        'name', 
        'role', // e.g., "Senior Graphic Designer"
        'college_id', 
        'course', 
        'year_level',
        'photo_url'
    ];


    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }
    
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    
}
