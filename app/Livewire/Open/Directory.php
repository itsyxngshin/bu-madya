<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Director;
use App\Models\AcademicYear;
use App\Models\SiteStat;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class Directory extends Component
{
    public $search = '';
    public $filter = 'All'; 
    public $selectedYearId = null; // Stores the currently selected Academic Year ID
    public $visitorCount = 1;

    public function mount()
    {
        // 1. Determine Default Year
        // Try to get the active year first, otherwise fallback to the latest one
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            $activeYear = AcademicYear::latest('id')->first();
        }
        
        $this->selectedYearId = $activeYear?->id;

        // ... (Visitor count logic remains unchanged) ...
        if (!Session::has('has_visited_site')) {
            SiteStat::where('key', 'visitor_count')->increment('value');
            Session::put('has_visited_site', true);
        }
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    // Computed property for the dropdown options
    public function getAcademicYearsProperty()
    {
        return AcademicYear::orderBy('id', 'desc')->get();
    }

    public function render()
    {
        // 1. Get the Current Year Object for display
        $displayYear = AcademicYear::find($this->selectedYearId);

        // 2. Start Querying POSITIONS (Directors)
        $query = Director::query()
            // Eager load the assignment for the SELECTED year only
            ->with(['assignments' => function ($q) {
                $q->where('academic_year_id', $this->selectedYearId)
                  ->with('user.profile.college');
            }]);

        // 3. Apply Filters
        if ($this->filter === 'Executive') {
            $query->where('order', '<=', 30);
        } elseif ($this->filter === 'Envoys') {
            $query->where('order', '>', 30);
        }

        // 4. Apply Search
        if (!empty($this->search)) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('assignments.user', fn($u) => $u->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        // 5. Get Results
        $results = $query->orderBy('order', 'asc')->get();

        // 6. Sort: Filled positions first, Vacant last
        $filled = $results->filter(fn($d) => $d->assignments->isNotEmpty());
        $vacant = $results->filter(fn($d) => $d->assignments->isEmpty());

        return view('livewire.open.directory', [
            'officers' => $filled->merge($vacant),
            'currentYearLabel' => $displayYear?->year ?? 'N/A' // e.g. "2025-2026"
        ]);
    }
}