<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Scopes\ActiveYearScope;

class DirectorAssignment extends Model
{
    protected $table = 'director_assignments';
    
    protected $fillable = [
        'user_id', 
        'director_id', // This is the ID of the Title (e.g., DG, Director)
        'committee_id', // Nullable
        'academic_year_id',
        'title' // Optional custom string override
    ];

    // 1. The Person
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. The Title (e.g., "Director-General", "Director")
    public function director() // I recommend calling this 'position' for clarity
    {
        return $this->belongsTo(Director::class, 'director_id');
    }
    

    // 3. The Committee (Optional - will be null for DG)
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    // 4. The Year
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /* 
     protected static function booted()
    {
        static::addGlobalScope(new ActiveYearScope);
    }
        */ 

    
}
