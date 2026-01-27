<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\Project; 
use App\Models\SiteStat; 
use Illuminate\Support\Facades\Session; 

#[Layout('layouts.madya-template')]
class ProjectsShow extends Component
{
    public Project $project;
    public $visitorCount = 1;

    public function mount(Project $project)
    {
        $this->project = $project;
        
        // Visitor Counter Logic (Keep as is)
        if (!Session::has('has_visited_site')) {
            SiteStat::where('key', 'visitor_count')->increment('value');
            Session::put('has_visited_site', true);
        }
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');

        // [CRITICAL UPDATE] Add 'evaluation' to the eager load list
        $this->project->load([
            'category', 
            'objectives', 
            'galleries',
            'sdgs',
            'academicYear',
            'proponents',
            'projectLinkages.linkage',
            'evaluation' // <--- ADD THIS LINE
        ]);
    }

    public function render()
    {
        return view('livewire.director.projects-show');
    }
}