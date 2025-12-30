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
    public function before($user, $ability)
    {
        // Only Role 1 (Admin) gets to bypass everything.
        // Directors (Role 2) must go through the 'update' checks so we can enforce Academic Year locks.
        if ($user->role_id === 1) {
            return true;
        }
        
        return null; // Fall through to specific methods
    }
    
    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project)
    {
        // 1. GET CURRENT ACADEMIC YEAR
        $currentYear = AcademicYear::current();

        // 2. CHECK: IS THE PROJECT LOCKED?
        // If project belongs to a past year, NO ONE (except Admin) can edit it.
        if ($project->academic_year_id && $project->academic_year_id !== $currentYear?->id) {
            return false; 
        }
        // 3. CHECK: AUTHORIZATION
        // Allow if User is the Proponent (Owner)
        // Check if relationship exists to avoid crash
        if ($project->project_proponent && $user->id === $project->project_proponent->user_id) {
            return true;
        }

        // 4. CHECK: DIRECTOR OVERRIDE
        // Allow if User is an Active Director (Optional: Check if assigned to this specific project category?)
        if ($user->role_id === 2 && $user->directorAssignment?->is_active) {
            return true;
        }

        return false;
    }
}