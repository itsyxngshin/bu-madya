<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\Project;
use App\Models\SiteStat;
use Illuminate\Support\Facades\Session;

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

    public function getYearsProperty()
    {
        // Grabs all unique academic years currently in the database
        return Project::query()
            ->select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');
    }

    public function render()
    {
        $query = Project::query()->with('category');

        // 1. Category Filter
        if ($this->category !== 'All') {
            $query->whereHas('category', function ($q) {
                $q->where('name', $this->category);
            });
        }

        // 2. Academic Year Filter
        if ($this->academicYear !== 'All') {
            $query->where('academic_year', $this->academicYear);
        }

        // 3. Sorting: Latest Implementation Date Onwards
        $query->orderBy('implementation_date', 'desc');

        return view('livewire.director.projects-index', [
            'projects' => $query->get(),
            // Pass categories here if you want them dynamic, or keep hardcoded in view
            'categories' => ['Community Outreach', 'Capacity Building', 'Environmental', 'Policy Advocacy', 'Partnership']
        ]);
    }
}
