<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Director;
use App\Models\AcademicYear;
use App\Models\SiteStat;
use App\Models\User;
use App\Models\DirectorAssignment;
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

    protected function getLegacyDirectors($yearId)
    {
        // 1. DEFINE YOUR DATA HERE
        $legacyData = [
            // Example for Academic Year ID 5
            6 => [
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'President',
                    'order'         => 0, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'John Cyril L. Yee',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  null,
                    'year_level'    => '4th Year',
                    'college_slug'  => 'bu-cal', // Used for badge text (CSSP)
                ],
                [
                    'position_name' => 'Secretary-General',
                    'order'         => 5,
                    'name'          => 'Dick Harrence Dela Vega',
                    'photo'         => null, 
                    'course'        => 'BS Bilogy',
                    'year_level'    => '4th Year',
                    'college_slug'  => 'bu-cs',
                ],
            ],
            // Add other years as needed...
        ];

        // If no data for this year, return empty collection
        if (!isset($legacyData[$yearId])) {
            return collect([]);
        }

        // 2. CONVERT ARRAY TO ELOQUENT MODELS
        // We map the array into actual Objects so the Blade view works without changes.
        return collect($legacyData[$yearId])->map(function ($item, $index) {
            
            // A. Mock the College Model
            $college = new \App\Models\College([
                'id'   => 99900 + $index,
                'slug' => $item['college_slug']
            ]);

            // B. Mock the Profile Model
            $profile = new \App\Models\Profile([
                'id'         => 99900 + $index,
                'course'     => $item['course'],
                'year_level' => $item['year_level'],
            ]);
            $profile->setRelation('college', $college);

            // C. Mock the User Model
            $user = new \App\Models\User([
                'id'                 => 99900 + $index,
                'name'               => $item['name'],
                'profile_photo_path' => $item['photo'],
                'username'           => 'legacy-user-' . $index, // Dummy username
            ]);
            $user->setRelation('profile', $profile);

            // D. Mock the Assignment Model (Pivot)
            $assignment = new \App\Models\DirectorAssignment([
                'id'    => 99900 + $index,
                'title' => $item['position_name'], // Default title
            ]);
            $assignment->setRelation('user', $user);

            // E. Mock the Director Model (The Position)
            $director = new \App\Models\Director([
                'id'    => 88800 + $index,
                'name'  => $item['position_name'],
                'order' => $item['order'],
            ]);

            // Attach the assignment to the director
            $director->setRelation('assignments', collect([$assignment]));

            return $director;
        });
    }

    public function render()
    {
        $displayYear = AcademicYear::find($this->selectedYearId);

        // 1. Query REAL Directors from DB
        $query = Director::query()
            ->with(['assignments' => function ($q) {
                $q->where('academic_year_id', $this->selectedYearId)
                  ->with('user.profile.college');
            }]);

        // ... (Existing Filters) ...
        if ($this->filter === 'Executive') {
            $query->where('order', '<=', 30);
        } elseif ($this->filter === 'Envoys') {
            $query->where('order', '>', 30);
        }

        // ... (Existing Search) ...
        if (!empty($this->search)) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('assignments.user', fn($u) => $u->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        // 2. Get Real Results
        $realResults = $query->orderBy('order', 'asc')->get();

        // 3. Get Hardcoded Results
        $legacyResults = $this->getLegacyDirectors($this->selectedYearId);

        // 4. Merge Both Collections
        // We merge legacy items into real items
        $allResults = $realResults->merge($legacyResults);

        // 5. Re-Sort based on 'order' to ensure hardcoded ones appear in correct slots
        $sortedResults = $allResults->sortBy('order');

        // 6. Split Filled/Vacant (Same logic as before)
        $filled = $sortedResults->filter(fn($d) => $d->assignments->isNotEmpty());
        $vacant = $sortedResults->filter(fn($d) => $d->assignments->isEmpty());

        return view('livewire.open.directory', [
            'officers' => $filled->merge($vacant),
            'currentYearLabel' => $displayYear?->year ?? 'N/A'
        ]);
    }
}