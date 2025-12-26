<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\Project;

#[Layout('layouts.madya-template')]
class ProjectsIndex extends Component
{
    public $category = 'All';

    public function setCategory($cat)
    {
        $this->category = $cat;
    }

    public function render()
    {
        $query = Project::query()
            ->with('category'); // Eager load category for performance
 
        if ($this->category !== 'All') {
            // Filter where the RELATED category name matches
            $query->whereHas('category', function ($q) {
                $q->where('name', $this->category);
            });
        }

        return view('livewire.director.projects-index', [
            'projects' => $query->latest()->get()
        ]);
    } 
}
