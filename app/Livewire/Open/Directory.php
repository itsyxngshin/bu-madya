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
                    'order'         => 1, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'John Cyril L. Yee',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'AB Journalism',
                    'year_level'    => '3rd Year',
                    'college_slug'  => 'bu-cal', // Used for badge text (CSSP)
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Culture',
                    'order'         => 2, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Xyra B. Belen',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'N/A',
                    'year_level'    => '3rd Year',
                    'college_slug'  => 'bu-cssp', // Used for badge text (CSSP)
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Communications',
                    'order'         => 3, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Darlene Fabul',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'N/A',
                    'year_level'    => '3rd Year',
                    'college_slug'  => 'bu-cs', // Used for badge text (CSSP)
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Science and Technology',
                    'order'         => 4, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'John Vincent Dy',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'N/A',
                    'year_level'    => '4th Year',
                    'college_slug'  => 'bu-cs', // Used for badge text (CSSP)
                ],
                [
                    'position_name' => 'Secretary-General',
                    'order'         => 8,
                    'name'          => 'Dick Harrence Dela Vega',
                    'photo'         => null, 
                    'course'        => 'BS Biology',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cs',
                ],
                [
                    'position_name' => 'Deputy Secretary-General',
                    'order'         => 9,
                    'name'          => 'Glenda Ante',
                    'photo'         => null, 
                    'course'        => 'AB Journalism',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cal',
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Education',
                    'order'         => 6, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Erwin Banares',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BS Biology',
                    'year_level'    => '4th Year',
                    'college_slug'  => 'bu-cs', // Used for badge text (CSSP)
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Social Sciences',
                    'order'         => 7, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Jesica Mae Rico',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BS Biology',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cssp', // Used for badge text (CSSP)
                ],

            ],
            7 => [
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'President',
                    'order'         => 1, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Patrick Anthony Nota',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BS Mining Engineering',
                    'year_level'    => '4th Year',
                    'college_slug'  => 'bu-ceng', // Used for badge text (CSSP)
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Education',
                    'order'         => 2, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Mariz Anne Columna',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BSBA Human Resource Management',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cbem', // Used for badge text (CSSP)
                ], 
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Science and Technology',
                    'order'         => 3, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Edric Ian Vargas',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BS Biology',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cbem', // Used for badge text (CSSP)
                ], 
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Culture',
                    'order'         => 4, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Sharmaine Granatin',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'AB Political Science',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cssp', 
                ], 
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Social Sciences',
                    'order'         => 5, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Jesica Rico',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'AB Political Science',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cssp', 
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Vice President for Communication',
                    'order'         => 6, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Glenda Ante',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'AB Journalism',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cal', 
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Secretary-General',
                    'order'         => 7, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'John Cyril L. Yee',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'AB Journalism',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cal', 
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Deputy Secretary-General',
                    'order'         => 8, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Carl Andre Bongalos',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BS Computer Science',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cs', 
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Finance Officer',
                    'order'         => 9, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Jennilyn Santos',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BSBA Human Resource Management',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cbem', 
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Audit',
                    'order'         => 10, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Karl Renz Llana',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BS Chemistry',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cs', 
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Auditor',
                    'order'         => 10, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Ronald Jude Arroyo',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BSBA Human Resource Management',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cbem', 
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Business Manager',
                    'order'         => 11, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Ronald Jude Arroyo',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'BSBA Human Resource Management',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cbem', 
                ],
                [
                    // DIRECTOR (Position Details)
                    'position_name' => 'Jesus Andrew Balmas',
                    'order'         => 12, // Lower number = appears first
                    
                    // USER (Person Details)
                    'name'          => 'Public Information Officer',
                    'photo'         =>  null, // Path in public/
                    
                    // PROFILE (Academic Details)
                    'course'        =>  'AB Political Science',
                    'year_level'    => 'N/A',
                    'college_slug'  => 'bu-cssp', 
                ],


            ]
            // Add other years as needed...
        ];

        // If no data for this year, return empty collection
        if (!isset($legacyData[$yearId])) {
            return collect([]);
        }

        // 2. CONVERT ARRAY TO ELOQUENT MODELS
        // We map the array into actual Objects so the Blade view works without changes.
        return collect($legacyData[$yearId])->map(function ($item, $index) {
            
            // A. Mock the College
            // FIX: Don't pass ID in the array. Set it manually.
            $college = new \App\Models\College([
                'slug' => $item['college_slug']
            ]);
            $college->id = 99900 + $index; // Manually set ID

            // B. Mock the Profile
            $profile = new \App\Models\Profile([
                'course'     => $item['course'],
                'year_level' => $item['year_level'],
            ]);
            $profile->id = 99900 + $index; // Manually set ID
            $profile->setRelation('college', $college);

            // C. Mock the User
            $user = new \App\Models\User([
                'name'               => $item['name'],
                'profile_photo_path' => $item['photo'],
                'username'           => 'legacy-user-' . $index,
            ]);
            $user->id = 99900 + $index; // Manually set ID
            $user->setRelation('profile', $profile);

            // D. Mock the Assignment
            $assignment = new \App\Models\DirectorAssignment([
                'title' => $item['position_name'],
            ]);
            $assignment->id = 99900 + $index; // Manually set ID
            $assignment->setRelation('user', $user);

            // E. Mock the Director (Position)
            $director = new \App\Models\Director([
                'name'  => $item['position_name'],
                'order' => $item['order'],
            ]);
            $director->id = 88800 + $index; // Manually set ID 

            // Link them
            $director->setRelation('assignments', collect([$assignment]));

            return $director;
        });
    }

    public function render()
    {
        $displayYear = AcademicYear::find($this->selectedYearId);
        
        // Check if we are viewing the "Active/Current" year
        // (Assumes you have an 'is_active' column, or you can check against the latest ID)
        $isViewingCurrentYear = $displayYear?->is_active;

        // 1. Get Real Data from DB
        $query = Director::query()
            ->with(['assignments' => function ($q) {
                $q->where('academic_year_id', $this->selectedYearId)
                  ->with('user.profile.college');
            }]);

        // ... (Filters & Search logic remains the same) ...
        if ($this->filter === 'Executive') {
            $query->where('order', '<=', 30);
        } elseif ($this->filter === 'Envoys') {
            $query->where('order', '>', 30);
        }

        if (!empty($this->search)) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('assignments.user', fn($u) => $u->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        $realResults = $query->orderBy('order', 'asc')->get();

        // 2. Get Legacy Data
        $legacyResults = $this->getLegacyDirectors($this->selectedYearId);

        // 3. Merge Both
        $allResults = $realResults->merge($legacyResults)->sortBy('order');

        // ---------------------------------------------------------
        // 4. THE FIX: Hide "Vacant" positions for Past Years
        // ---------------------------------------------------------
        if (!$isViewingCurrentYear) {
            $allResults = $allResults->filter(function ($director) {
                // Keep only if there is someone assigned (Real or Legacy)
                return $director->assignments->isNotEmpty();
            });
        }

        // 5. Separate Filled vs Vacant (For current year display logic)
        $filled = $allResults->filter(fn($d) => $d->assignments->isNotEmpty());
        $vacant = $allResults->filter(fn($d) => $d->assignments->isEmpty());

        return view('livewire.open.directory', [
            // If past year, $vacant will be empty, effectively hiding the positions
            'officers' => $filled->merge($vacant),
            'currentYearLabel' => $displayYear?->year ?? 'N/A'
        ]);
    }
}