<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\AcademicYear; // Import the model
use App\Models\SiteStat;
use Illuminate\Support\Facades\Session;

#[Layout('layouts.madya-template')]
class ProjectsIndex extends Component
{
    use WithPagination;

    public $category = 'All';
    public $academicYearId = 'All'; // Changed to track ID
    public $visitorCount = 1;

    public function updatedCategory() { $this->resetPage(); }
    public function updatedAcademicYearId() { $this->resetPage(); }

    public function mount()
    {
        if (!Session::has('has_visited_site')) {
            SiteStat::where('key', 'visitor_count')->increment('value');
            Session::put('has_visited_site', true);
        }
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
    }

    // Computed property to populate the Year dropdown
    public function getAcademicYearsProperty()
    {
        // Fetch all academic years, sorted descending (assuming 'year' is the label column)
        return AcademicYear::orderBy('id', 'desc')->get();
    }

    public function render()
    {
        // Eager load 'category' AND 'academicYear' to prevent N+1 queries
        $query = Project::query()->with(['category', 'academicYear']);

        // 1. Category Filter
        if ($this->category !== 'All') {
            $query->whereHas('category', function ($q) {
                $q->where('name', $this->category);
            });
        }

        // 2. Academic Year Filter (Using the ID)
        if ($this->academicYearId !== 'All') {
            $query->where('academic_year_id', $this->academicYearId);
        }

        // 3. Sort by Implementation Date
        $query->orderBy('implementation_date', 'desc');

        return view('livewire.director.projects-index', [
            'projects' => $query->paginate(9),
            // You can also fetch categories dynamically from your DB if needed
            'categories' => ['Community Outreach', 'Capacity Building', 'Environmental', 'Policy Advocacy', 'Partnership'] 
        ]);
    }
}