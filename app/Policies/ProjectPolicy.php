<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use App\Models\AcademicYear;

class ProjectPolicy
{
    /**
     * Bypasses checks for Super Admins ONLY.
     */
    public function update(User $user, Project $project)
    {
        // ----------------------------------------------------
        // 1. SUPER USER OVERRIDE (Admins & Directors)
        // ----------------------------------------------------
        // We check this FIRST. If they are an Admin or Director, 
        // we approve immediately. They bypass the "Year Lock".
        
        // Note: Using role_name is safer than 'role_id === 2'
        if (in_array($user->role->role_name, ['administrator', 'director'])) {
            return true;
        }

        // ----------------------------------------------------
        // 2. CHECK: IS THE PROJECT LOCKED?
        // ----------------------------------------------------
        // Now we check the lock. If it's an old project, regular 
        // users (owners) get blocked here.
        $currentYear = AcademicYear::current();

        if ($project->academic_year_id && $project->academic_year_id !== $currentYear?->id) {
            return false; 
        }

        // ----------------------------------------------------
        // 3. CHECK: OWNER (PROPONENT) AUTHORIZATION
        // ----------------------------------------------------
        // If it's the current year, allow the owner to edit.
        if ($project->project_proponent && $user->id === $project->project_proponent->user_id) {
            return true;
        }

        return false;
    }
}