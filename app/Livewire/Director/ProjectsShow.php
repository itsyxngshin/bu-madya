<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\Project; // [CRITICAL FIX] Import the Model
use App\Models\SiteStat; // Import the SiteStat model
use Illuminate\Support\Facades\Session; // Import the Session facade

#[Layout('layouts.madya-template')]
class ProjectsShow extends Component
{
    public Project $project;
    public $visitorCount = 1;

    public function mount(Project $project)
    {
        $this->project = $project;
        
        // 1. Check if this specific user has already been counted in this session
        if (!Session::has('has_visited_site')) {
            
            // 2. Increment the database value securely
            SiteStat::where('key', 'visitor_count')->increment('value');
            
            // 3. Mark this user as counted for this browser session
            Session::put('has_visited_site', true);
        }

        // 4. Retrieve the current total (cache it briefly to reduce DB queries on high traffic)
        // We remember it for 10 minutes, or fetch directly if you want instant real-time
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');

        // [OPTIMIZATION] Eager load ALL relationships used in the view
        $this->project->load([
            'category', 
            'objectives', 
            'galleries',
            'sdgs',
            'academicYear',           // For the "AY 2024-2025" badge
            'proponents',             // For the lead proponents list
            'projectLinkages.linkage' // For the partners list (hybrid accessor)
        ]);
    }

    public function render()
    {
        return view('livewire.director.projects-show');
    }
}