<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\Project; // [CRITICAL FIX] Import the Model

#[Layout('layouts.madya-template')]
class ProjectsShow extends Component
{
    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
        
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