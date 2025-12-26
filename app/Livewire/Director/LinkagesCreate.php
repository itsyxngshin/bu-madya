<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Linkage;
use Livewire\WithFileUploads;
use App\Models\LinkageType;
use App\Models\LinkageStatus;
use App\Models\Project; 
use App\Models\Sdg; // Assuming you have an SDG model seeded
use App\Models\LinkageActivity;
use Livewire\Attributes\Layout; 
use Illuminate\Validation\Rule;

#[Layout('layouts.madya-template')]
class LinkagesCreate extends Component
{
    use WithFileUploads;
    // Form Properties
    public $name;
    public $acronym; // Added based on schema
    public $type_id;
    public $status_id;
    public $logo;  // The actual file object
    public $cover; // The actual file object
    public $established_at; // "Partner Since"
    public $scope; // Not in schema, but we will handle it
    public $logo_path;
    public $cover_img_path;
    public $description;
    public $email;
    public $website;
    public $address;
    public $slug;
    public $selectedProjects = [];

    // Relationships / Dynamic Inputs
    public $engagements = [
        ['date' => '', 'type' => '', 'title' => '', 'desc' => '']
    ];
    public $selectedSdgs = [];

    // Modal Properties
    public $showProjectModal = false;
    public $newProjectTitle = '';
    public $newProjectStatus = 'Ongoing';
    public $newProjectCategoryId = ''; // 👈 REQUIRED by migration

    // Helper to populate the Category Dropdown
    public function getProjectCategoriesProperty()
    {
        return ProjectCategory::orderBy('name')->get();
    }

    public function createProject()
    {
        // 1. Validation
        $this->validate([
            'newProjectTitle' => 'required|min:3|unique:projects,title',
            'newProjectCategoryId' => 'required|exists:project_categories,id', // Must select a category
        ]);

        // 2. Create with REQUIRED fields from migration
        $project = Project::create([
            'title' => $this->newProjectTitle,
            'slug' => Str::slug($this->newProjectTitle), // 👈 Generate slug automatically
            'project_category_id' => $this->newProjectCategoryId,
            'status' => $this->newProjectStatus,
            // 'implementation_date' => now(), // Optional: default to today?
        ]);

        // 3. Select and Close
        $this->selectedProjects[] = $project->id;
        $this->reset(['newProjectTitle', 'newProjectStatus', 'newProjectCategoryId', 'showProjectModal']);
        
        // Optional: Notify user
        // session()->flash('message', 'Project created successfully!');
    }

    public function mount()
    {
        // Initialize with default date or first option if needed
    }

    public function getAllProjectsProperty()
    {
        // Fetch active projects to choose from
        return Project::orderBy('title')->get();
    }

    // 3. Toggle Action
    public function toggleProject($id)
    {
        if (in_array($id, $this->selectedProjects)) {
            $this->selectedProjects = array_diff($this->selectedProjects, [$id]);
        } else {
            $this->selectedProjects[] = $id;
        }
    }
    public function updatedName($value)
    {
        // Only auto-generate if the user hasn't manually typed a custom slug yet
        // OR simply overwrite it for convenience in a "Create" form
        $this->slug = Str::slug($value);
    }

    protected function rules()
    {
        return [
            // Ensure slug is unique in the linkages table
            'name' => 'required|min:3',
            'type_id' => 'required|exists:linkage_types,id',
            'status_id' => 'required|exists:linkage_statuses,id',
            'logo' => 'nullable|image|max:2048', // 2MB Max
            'cover' => 'nullable|image|max:4096', // 4MB Max
            'email' => 'nullable|email',
            'engagements.*.title' => 'required_with:engagements.*.date',
            'slug' => 'required|alpha_dash|unique:linkages,slug', 

        ];
    }
    // --- Computed Properties for Dropdowns ---
    public function getTypesProperty()
    {
        return LinkageType::all();
    }

    public function getStatusesProperty()
    {
        return LinkageStatus::all();
    }

    public function getAllSdgsProperty()
    {
        // Assuming your SDG table has 'id', 'name', and 'color' columns
        return Sdg::orderBy('id')->get();
    }

    // --- Actions ---

    public function addEngagement()
    {
        $this->engagements[] = ['date' => '', 'type' => '', 'title' => '', 'desc' => ''];
    }

    public function removeEngagement($index)
    {
        unset($this->engagements[$index]);
        $this->engagements = array_values($this->engagements); // Re-index array
    }

    public function toggleSdg($id)
    {
        if (in_array($id, $this->selectedSdgs)) {
            $this->selectedSdgs = array_diff($this->selectedSdgs, [$id]);
        } else {
            $this->selectedSdgs[] = $id;
        }
    }

    public function save()
    {
        $this->validate();
        $logoPath = null;
        if ($this->logo) {
            // Stores in storage/app/public/linkages/logos
            $logoPath = $this->logo->store('linkages/logos', 'public');
        }

        $coverPath = null;
        if ($this->cover) {
            $coverPath = $this->cover->store('linkages/covers', 'public');
        }

        if (!empty($this->selectedProjects)) {
            // Assumes you have belongsToMany('projects') in Linkage model
            $linkage->projects()->sync($this->selectedProjects);
        }

        // 1. Create Main Linkage
        $linkage = Linkage::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'acronym' => $this->acronym,
            'linkage_type_id' => $this->type_id,
            'linkage_status_id' => $this->status_id,
            'established_at' => $this->established_at, // Ensure Date format in UI or Cast here
            'logo_path' => $logoPath, // 👈 Save the generated path
            'cover_img_path' => $coverPath, // 👈 Save the generated path
            'description' => $this->description, // We can append Scope here if no column exists: . " Scope: " . $this->scope
            'email' => $this->email,
            'website' => $this->website,
            'address' => $this->address,
        ]);

        // 2. Save Engagements (Activities)
        foreach ($this->engagements as $eng) {
            if (!empty($eng['title'])) {
                $linkage->activities()->create([
                    'title' => $eng['title'],
                    'activity_date' => date('Y-m-d', strtotime($eng['date'])), // Simple parsing
                    'description' => $eng['desc'] . " (Type: {$eng['type']})", // Merging type into desc for now
                ]);
            }
        }

        if (!empty($this->selectedSdgs)) {
            $linkage->sdgs()->sync($this->selectedSdgs);
        }

        // 3. Sync SDGs
        // Assuming you have a ManyToMany relationship set up in Linkage model
        // $linkage->sdgs()->sync($this->selectedSdgs);

        session()->flash('message', 'Partner successfully created!');
        return redirect()->route('linkages.index');
    }

    public function render()
    {
        return view('livewire.director.linkages-create');
    }
}