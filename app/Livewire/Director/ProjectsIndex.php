<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\Project;

#[Layout('layouts.madya-template')]
class ProjectsIndex extends Component
{
    public $category = 'All';
    public $visitorCount = 1;
    public function mount()
    {
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
    }

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
