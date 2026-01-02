<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    protected $fillable = [
        'committee_id', 
        'academic_year_id',
        'title', 
        'user_id', // Optional: if linking to a registered user
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
