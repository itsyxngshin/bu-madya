<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\DirectorAssignment;
use App\Models\AcademicYear;
use App\Models\Director;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class Directory extends Component
{
public $search = '';
    public $filter = 'All'; 

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function render()
    {
        // 1. Get Active Year
        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $activeYear?->id ?? 0;

        // 2. Start Querying POSITIONS (Directors), not Users
        $query = Director::query()
            // Eager load the assignment for the CURRENT year only
            ->with(['assignments' => function ($q) use ($yearId) {
                $q->where('academic_year_id', $yearId)
                  ->with('user.profile.college');
            }]);

        // 3. Apply Filters (Executive vs Envoys)
        if ($this->filter === 'Executive') {
            $query->where('order', '<=', 30);
        } elseif ($this->filter === 'Envoys') {
            $query->where('order', '>', 30);
        }

        // 4. Apply Search (Search by Position Name OR User Name)
        if (!empty($this->search)) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('assignments.user', fn($u) => $u->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        // 5. Get Results sorted by hierarchy order first
        $results = $query->orderBy('order', 'asc')->get();

        // 6. SORTING MAGIC: Move Vacant to Bottom
        // We split the collection into two: Filled and Vacant, then merge them.
        $filled = $results->filter(fn($d) => $d->assignments->isNotEmpty());
        $vacant = $results->filter(fn($d) => $d->assignments->isEmpty());

        return view('livewire.open.directory', [
            'officers' => $filled->merge($vacant), // Active first, Vacant last
            'currentYear' => $activeYear?->name ?? 'N/A'
        ]);
    }
}