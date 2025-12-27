<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads; 
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;

// ✅ FIXED: Added missing Models
use App\Models\Linkage;
use App\Models\LinkageType;
use App\Models\LinkageStatus;
use App\Models\Project; 
use App\Models\ProjectCategory; // Needed for the modal dropdown
use App\Models\Sdg; 
use App\Models\LinkageActivity;

#[Layout('layouts.madya-template')]
class LinkagesCreate extends Component
{
    use WithFileUploads;

    // Form Properties
    public $name;
    public $acronym;
    public $type_id;
    public $status_id;
    public $logo;
    public $cover;
    public $established_at;
    public $scope;
    public $logo_path;
    public $cover_img_path;
    public $description;
    public $email;
    public $website;
    public $address;
    public $slug;
    
    // Arrays
    public $selectedProjects = [];
    public $engagements = [['date' => '', 'type' => '', 'title' => '', 'desc' => '']];
    public $selectedSdgs = [];

    // Modal Properties
    public $showProjectModal = false;
    public $newProjectTitle = '';
    public $newProjectStatus = 'Ongoing';
    public $newProjectCategoryId = '';

    // --- Computed Properties (Fixes 'Property not found' errors) ---
    
    public function getTypesProperty()
    {
        return LinkageType::orderBy('name')->get();
    }

    public function getStatusesProperty()
    {
        return LinkageStatus::all();
    }

    public function getAllSdgsProperty()
    {
        return Sdg::orderBy('id')->get();
    }

    public function getAllProjectsProperty()
    {
        return Project::orderBy('title')->get();
    }

    public function getProjectCategoriesProperty()
    {
        return ProjectCategory::orderBy('name')->get();
    }

    // --- Actions ---

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function addEngagement()
    {
        $this->engagements[] = ['date' => '', 'type' => '', 'title' => '', 'desc' => ''];
    }

    public function removeEngagement($index)
    {
        unset($this->engagements[$index]);
        $this->engagements = array_values($this->engagements);
    }

    public function toggleSdg($id)
    {
        if (in_array($id, $this->selectedSdgs)) {
            $this->selectedSdgs = array_diff($this->selectedSdgs, [$id]);
        } else {
            $this->selectedSdgs[] = $id;
        }
    }

    public function toggleProject($id)
    {
        if (in_array($id, $this->selectedProjects)) {
            $this->selectedProjects = array_diff($this->selectedProjects, [$id]);
        } else {
            $this->selectedProjects[] = $id;
        }
    }

    public function createProject()
    {
        $this->validate([
            'newProjectTitle' => 'required|min:3|unique:projects,title',
            'newProjectCategoryId' => 'required|exists:project_categories,id',
        ]);

        $project = Project::create([
            'title' => $this->newProjectTitle,
            'slug' => Str::slug($this->newProjectTitle),
            'project_category_id' => $this->newProjectCategoryId,
            'status' => $this->newProjectStatus,
        ]);

        $this->selectedProjects[] = $project->id;
        $this->reset(['newProjectTitle', 'newProjectStatus', 'newProjectCategoryId', 'showProjectModal']);
    }

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'type_id' => 'required|exists:linkage_types,id',
            'status_id' => 'required|exists:linkage_statuses,id',
            'logo' => 'nullable|image|max:2048',
            'cover' => 'nullable|image|max:4096',
            'email' => 'nullable|email',
            'engagements.*.title' => 'required_with:engagements.*.date',
            'slug' => 'required|alpha_dash|unique:linkages,slug', 
        ];
    }

    public function save()
    {
        $this->validate();

        // 1. Handle File Uploads
        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('linkages/logos', 'public');
        }

        $coverPath = null;
        if ($this->cover) {
            $coverPath = $this->cover->store('linkages/covers', 'public');
        }

        // 2. Create Main Linkage (Database Insert)
        // ✅ FIXED: Must create this FIRST before attaching projects
        $linkage = Linkage::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'acronym' => $this->acronym,
            'linkage_type_id' => $this->type_id,
            'linkage_status_id' => $this->status_id,
            'established_at' => $this->established_at,
            'logo_path' => $logoPath,
            'cover_img_path' => $coverPath,
            'description' => $this->description,
            'email' => $this->email,
            'website' => $this->website,
            'address' => $this->address,
        ]);

        // 3. Save Relationships (Now that $linkage exists)
        
        // Projects
        if (!empty($this->selectedProjects)) {
            $linkage->projects()->sync($this->selectedProjects);
        }

        // SDGs
        if (!empty($this->selectedSdgs)) {
            $linkage->sdgs()->sync($this->selectedSdgs);
        }

        // Engagements
        foreach ($this->engagements as $eng) {
            if (!empty($eng['title'])) {
                $linkage->activities()->create([
                    'title' => $eng['title'],
                    'activity_date' => date('Y-m-d', strtotime($eng['date'])),
                    'description' => $eng['desc'] . ($eng['type'] ? " (Type: {$eng['type']})" : ''),
                ]);
            }
        }

        session()->flash('message', 'Partner successfully created!');
        return redirect()->route('linkages.index');
    }

    public function render()
    {
        return view('livewire.director.linkages-create');
    }
} 