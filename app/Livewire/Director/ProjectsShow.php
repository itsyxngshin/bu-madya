<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class ProjectsShow extends Component
{
    public Project $project;

    // Livewire automatically resolves {project:slug} from the URL into this property
    public function mount(Project $project)
    {
        $this->project = $project;
        
        // Load relationships efficiently (SDGs, Objectives, Category)
        $this->project->load(['category', 'objectives', 'sdgs']);
    }

    public function render()
    {
        return view('livewire.director.projects-show');
    }
}
