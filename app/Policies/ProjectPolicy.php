<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use App\Models\AcademicYear;

class ProjectPolicy
{
    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project)
    {
        // Super admin override feature
        // If you want Admins to ALWAYS be able to edit, keep this. 
        // If you want strict lockdown even for admins, remove it.
        if ($user->role?->role_name === 'administrator') {
            return true;
        }

        // GET CURRENT ACADEMIC YEAR
        $currentYear = AcademicYear::current();

        // 3. CHECK: IS THE PROJECT LOCKED? (Belongs to a past year)
        // If the project has a year, and it doesn't match the current active one
        // -> DISALLOW ACCESS
        if ($project->academic_year_id && $project->academic_year_id !== $currentYear?->id) {
            return false; 
        }

        // 4. CHECK: IS USER STILL A DIRECTOR?
        // Adjust this check based on how you determine if someone is a director
        $isDirector = $user->directorAssignment && $user->directorAssignment->is_active; 
        
        if (!$isDirector) {
            return false;
        }

        // 5. CHECK: DOES USER OWN THE PROJECT? (Optional standard check)
        return $user->id === $project->project_proponent->user_id;

        // Proponent override: 
        if ($user->id === $project->project_proponent->user_id) {
            return true;
        }
    }
}