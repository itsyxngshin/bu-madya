<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    public function directorAssignment()
    {
        return $this->hasOne(DirectorAssignment::class)
                    ->where('academic_year_id', AcademicYear::current()->id);
    }

    // Helper: Get the User object directly (The person)
    // Usage: $committee->currentDirector->name
    public function currentDirector()
    {
        // We go through the assignment to get the user
        return $this->hasOneThrough(
            User::class,               // Target: User
            DirectorAssignment::class, // Bridge: Assignment
            'committee_id',            // FK on Assignment table
            'id',                      // FK on User table
            'id',                      // Local key on Committee
            'user_id'                  // Local key on Assignment
        )->where('director_assignments.academic_year_id', AcademicYear::current()->id);
    }
    public function members()
    {
        // Order by name so the list is neat
        return $this->hasMany(CommitteeMember::class)->orderBy('name');
    }
}
