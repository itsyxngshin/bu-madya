<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads; 
use Livewire\Attributes\Layout; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

// Models
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\AcademicYear;
use App\Models\ProjectObjective;
use App\Models\ProjectProponent; 
use App\Models\LinkageProject; 
use App\Models\Linkage;  
use App\Models\Sdg;

#[Layout('layouts.madya-template')]
class ProjectsCreate extends Component
{
    use WithFileUploads; 

    // 1. Basic Fields
    public $title = '';
    public $slug = ''; 
    public $project_category_id = '';
    public $status = 'Upcoming';
    public $date = '';
    public $location = '';
    public $beneficiaries = '';
    public $description = '';
    public $academic_year_id = '';
    
    // Image Upload
    public $coverImg; 

    // 2. Complex Lists
    public $proponents = [
        ['type' => 'user', 'id' => '', 'name' => ''] 
    ];
    public $categories = [];
    public $partners = [
        ['type' => 'database', 'id' => '', 'name' => '', 'role' => 'Partner']
    ]; 

    public $objectives = ['']; 
    
    public $impact_stats = [
        ['label' => '', 'value' => '']
    ]; 

    public $selectedSdgs = [];

    // 3. Dropdown Data
    public $users = [];             
    public $academic_years = [];
    public $availableLinkages = [];

    // ==========================================
    // INITIALIZATION
    // ==========================================
    public function mount()
    {
        $this->users = User::orderBy('name')->get();
        $this->categories = ProjectCategory::orderBy('name')->get();
        
        // Optional: Set a default (e.g., the first one)
        if ($this->categories->isNotEmpty()) {
            $this->project_category_id = $this->categories->first()->id;
        }
        $this->academic_years = AcademicYear::orderBy('is_active', 'desc')
                                            ->orderBy('name', 'desc')
                                            ->get();
 
        // Auto-select active year
        $activeYear = $this->academic_years->firstWhere('is_active', true);
        if ($activeYear) {
            $this->academic_year_id = $activeYear->id;
        }
        if (request()->has('date')) {
            $this->implementation_date = request()->query('date');
        }

        $this->availableLinkages = Linkage::orderBy('name')->get();
    }

    // ==========================================
    // DYNAMIC UPDATES
    // ==========================================
    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value); 
    }

    // ==========================================
    // LIST MANAGEMENT
    // ==========================================
    public function addProponent()
    {
        $this->proponents[] = ['type' => 'user', 'id' => '', 'name' => ''];
    }

    public function removeProponent($index)
    {
        unset($this->proponents[$index]);
        $this->proponents = array_values($this->proponents);
    }

    public function addObjective()
    {
        $this->objectives[] = '';
    }

    public function removeObjective($index)
    {
        unset($this->objectives[$index]);
        $this->objectives = array_values($this->objectives);
    }

    public function addPartner()
    {
        $this->partners[] = ['type' => 'database', 'id' => '', 'name' => '', 'role' => 'Partner'];
    }

    public function removePartner($index)
    {
        unset($this->partners[$index]);
        $this->partners = array_values($this->partners);
    }

    public function addStat()
    {
        $this->impact_stats[] = ['label' => '', 'value' => ''];
    }

    public function removeStat($index)
    {
        unset($this->impact_stats[$index]);
        $this->impact_stats = array_values($this->impact_stats);
    }

    public function toggleSdg($id)
    {
        if (in_array($id, $this->selectedSdgs)) {
            $this->selectedSdgs = array_diff($this->selectedSdgs, [$id]);
        } else {
            $this->selectedSdgs[] = $id;
        }
    }

    // ==========================================
    // SAVING LOGIC
    // ==========================================
    public function save()
    {
        $this->validate([
            'title' => 'required|min:5',
            'slug'  => ['required', 'alpha_dash', Rule::unique('projects', 'slug')],
            'date'  => 'required|date',
            'project_category_id' => 'required|exists:project_categories,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'coverImg' => 'nullable|image|max:5120', 
        ]);

        DB::transaction(function () {
            
            // A. Image Upload
            $imagePath = null;
            if ($this->coverImg) {
                $imagePath = $this->coverImg->store('projects', 'public');
            }

            // C. Create Project
            $project = Project::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'project_category_id' => $this->project_category_id,
                'academic_year_id' => $this->academic_year_id,
                'status' => $this->status,
                'implementation_date' => $this->date,
                'location' => $this->location,
                'beneficiaries' => $this->beneficiaries,
                'description' => $this->description,
                'cover_img' => $imagePath,
                
                // We use JSON for stats, but NOT for partners (we use the relationship table now)
                'impact_stats' => array_filter($this->impact_stats, fn($i) => !empty($i['value'])),
                'partners_list' => [], // Keeping empty to avoid confusion with the relationship
            ]);

            // D. Save Linkages (Hybrid: Database + Manual)
            foreach ($this->partners as $partner) {
                
                // Skip if both fields are empty
                if (empty($partner['id']) && empty($partner['name'])) continue;

                $linkageData = [
                    'project_id'  => $project->id,
                    'role'        => $partner['role'] ?? 'Partner',
                    'linkage_id'  => null,
                    'name' => null, 
                ];

                // CASE 1: Official Linkage
                if ($partner['type'] === 'database' && !empty($partner['id'])) {
                    $linkageData['linkage_id'] = $partner['id'];
                } 
                // CASE 2: Manual Text
                elseif ($partner['type'] === 'custom' && !empty($partner['name'])) {
                    $linkageData['name'] = $partner['name'];
                }

                LinkageProject::create($linkageData);
            }

            // E. Save Proponents (Multiple)
            foreach ($this->proponents as $prop) {
                $finalName = '';
                $finalUserId = null;

                if ($prop['type'] === 'user' && !empty($prop['id'])) {
                    $user = User::find($prop['id']);
                    if ($user) {
                        $finalName = $user->name;
                        $finalUserId = $user->id;
                    }
                } elseif (!empty($prop['name'])) {
                    $finalName = $prop['name'];
                }

                if (!empty($finalName)) {
                    ProjectProponent::create([
                        'project_id' => $project->id,
                        'user_id'    => $finalUserId,
                        'name'       => $finalName,
                        'type'       => 'Lead' 
                    ]);
                }
            }

            // F. Save Objectives
            foreach ($this->objectives as $obj) {
                if (!empty($obj)) {
                    ProjectObjective::create([
                        'project_id' => $project->id,
                        'objective' => $obj
                    ]);
                }
            }

            // G. Save SDGs
            if (!empty($this->selectedSdgs)) {
                $project->sdgs()->attach($this->selectedSdgs);
            }
        });

        session()->flash('message', 'Project successfully created!');
        return redirect()->route('projects.index');
    }

    public function render()
    {
        return view('livewire.director.projects-create', [
            'sdgs' => Sdg::orderBy('number')->get() 
        ]);
    }
}