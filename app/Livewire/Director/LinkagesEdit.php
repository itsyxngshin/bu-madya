<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Linkage;
use App\Models\LinkageType;
use App\Models\LinkageStatus;
use App\Models\Sdg;
use App\Models\Project;
use App\Models\ProjectCategory;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class LinkagesEdit extends Component
{
    use WithFileUploads;

    public Linkage $linkage; // The model instance

    // Form Properties
    public $name;
    public $slug;
    public $type_id;
    public $status_id;
    public $established_at;
    public $scope; 
    public $description;
    public $email;
    public $website;
    public $address;

    // File Uploads (New)
    public $logo; 
    public $cover;

    // Existing File Paths (For display)
    public $existingLogo;
    public $existingCover;

    // Relationships
    public $engagements = [];
    public $selectedSdgs = [];
    public $selectedProjects = [];

    // Modal
    public $showProjectModal = false;
    public $newProjectTitle = '';
    public $newProjectStatus = 'Ongoing';
    public $newProjectCategoryId = '';

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'slug' => 'required|alpha_dash|unique:linkages,slug,' . $this->linkage->id, // Ignore current ID
            'type_id' => 'required|exists:linkage_types,id',
            'status_id' => 'required|exists:linkage_statuses,id',
            'logo' => 'nullable|image|max:2048',
            'cover' => 'nullable|image|max:4096',
            'engagements.*.title' => 'required_with:engagements.*.date',
        ];
    }

    public function mount(Linkage $linkage)
    {
        $this->linkage = $linkage;

        // 1. Populate Basic Fields
        $this->name = $linkage->name;
        $this->slug = $linkage->slug;
        $this->type_id = $linkage->linkage_type_id;
        $this->status_id = $linkage->linkage_status_id;
        $this->established_at = $linkage->established_at ? $linkage->established_at->format('Y-m-d') : null;
        // Assume 'scope' might be part of description or a separate column. 
        // If it's not a column, we skip it or extract it. 
        // For this example, let's assume you added a 'scope' column as discussed.
        $this->scope = $linkage->scope; 
        
        $this->description = $linkage->description;
        $this->email = $linkage->email;
        $this->website = $linkage->website;
        $this->address = $linkage->address;

        // 2. Populate Files
        $this->existingLogo = $linkage->logo_path;
        $this->existingCover = $linkage->cover_img_path;

        // 3. Populate Relationships
        // Engagements
        foreach ($linkage->activities as $activity) {
            $this->engagements[] = [
                'id' => $activity->id, // Keep ID to update existing records
                'date' => $activity->activity_date->format('Y-m-d'),
                'title' => $activity->title,
                // Extract 'Type' from description if you merged it earlier, or just use description
                'type' => '', // You might need to parse this if you stored it in description
                'desc' => $activity->description,
            ];
        }
        // If empty, add one blank row
        if (empty($this->engagements)) {
            $this->addEngagement();
        }

        // SDGs (Pluck IDs)
        $this->selectedSdgs = $linkage->sdgs->pluck('id')->toArray();

        // Projects (Pluck IDs)
        $this->selectedProjects = $linkage->projects->pluck('id')->toArray();
    }

    // --- Actions ---

    public function updatedName($value)
    {
        // Only auto-update slug if it hasn't been manually customized (optional logic)
        // For simplicity, let's just update it if they are editing the name
        $this->slug = Str::slug($value);
    }

    public function addEngagement()
    {
        $this->engagements[] = ['id' => null, 'date' => '', 'type' => '', 'title' => '', 'desc' => ''];
    }

    public function removeEngagement($index)
    {
        // If it has an ID, we should probably mark it for deletion or delete it immediately.
        // For simplicity in this example, we just remove from the array. 
        // Actual deletion happens on save() by comparing IDs.
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

    // Modal Actions
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

    // Computed Properties
    public function getTypesProperty() { return LinkageType::all(); }
    public function getStatusesProperty() { return LinkageStatus::all(); }
    public function getAllSdgsProperty() { return Sdg::orderBy('id')->get(); }
    public function getAllProjectsProperty() { return Project::orderBy('title')->get(); }
    public function getProjectCategoriesProperty() { return ProjectCategory::orderBy('name')->get(); }

    public function save()
    {
        $this->validate();

        // 1. Handle Files
        $logoPath = $this->existingLogo;
        if ($this->logo) {
            // Delete old if exists? Optional.
            $logoPath = $this->logo->store('linkages/logos', 'public');
        }

        $coverPath = $this->existingCover;
        if ($this->cover) {
            $coverPath = $this->cover->store('linkages/covers', 'public');
        }

        // 2. Update Main Linkage
        $this->linkage->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'linkage_type_id' => $this->type_id,
            'linkage_status_id' => $this->status_id,
            'established_at' => $this->established_at,
            'scope' => $this->scope,
            'logo_path' => $logoPath,
            'cover_img_path' => $coverPath,
            'description' => $this->description,
            'email' => $this->email,
            'website' => $this->website,
            'address' => $this->address,
        ]);

        // 3. Sync SDGs & Projects
        $this->linkage->sdgs()->sync($this->selectedSdgs);
        $this->linkage->projects()->sync($this->selectedProjects);

        // 4. Handle Engagements (The tricky part: Create, Update, Delete)
        // Get current IDs from the form
        $currentEngagementIds = array_filter(array_column($this->engagements, 'id'));
        
        // Delete removed engagements
        $this->linkage->activities()->whereNotIn('id', $currentEngagementIds)->delete();

        // Create or Update
        foreach ($this->engagements as $eng) {
            if (!empty($eng['title'])) {
                $this->linkage->activities()->updateOrCreate(
                    ['id' => $eng['id']], // Find by ID
                    [
                        'title' => $eng['title'],
                        'activity_date' => $eng['date'],
                        'description' => $eng['desc'],
                        // 'type' => $eng['type'] // If you added a type column
                    ]
                );
            }
        }

        session()->flash('message', 'Partner profile updated successfully!');
        return redirect()->route('linkages.index');
    }

    public function render()
    {
        return view('livewire.director.linkages-edit');
    }
}