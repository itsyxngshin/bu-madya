<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
class ProjectsEdit extends Component
{
    use WithFileUploads;

    public Project $project; // The existing project model

    // 1. Basic Fields
    public $title = '';
    public $slug = '';
    public $project_category_id = ''; // Changed from $cat to ID
    public $status = '';
    public $date = '';
    public $location = '';
    public $beneficiaries = '';
    public $description = '';
    public $academic_year_id = '';

    // Image Upload
    public $coverImg;       // New upload (TemporaryUploadedFile)
    public $oldCoverImg;    // String path from DB (for display)

    // 2. Complex Lists
    public $proponents = [];
    public $partners = [];
    public $objectives = [];
    public $impact_stats = [];
    public $selectedSdgs = [];

    // 3. Dropdown Data
    public $users = [];
    public $academic_years = [];
    public $categories = [];
    public $availableLinkages = [];

    public $newGalleryImages = []; // For the file upload (TemporaryUploadedFile[])
    public $galleryInputs = [];    // For editing existing titles/descriptions

    // ==========================================
    // INITIALIZATION (Hydrate Form)
    // ==========================================
    public function mount(Project $project)
    {
        $this->project = $project;
        $this->authorize('update', $this->project);

        // 1. FIX: Eager Load Relationships
        // This ensures these properties are populated before we access them.
        // We use 'load' to fetch the related data from the database.
        $this->project->load(['objectives', 'sdgs', 'proponents', 'projectLinkages']);

        // A. Basic Fields
        $this->title = $project->title;
        $this->slug = $project->slug;
        $this->project_category_id = $project->project_category_id;
        $this->status = $project->status;
        $this->date = $project->implementation_date?->format('Y-m-d');
        $this->location = $project->location;
        $this->beneficiaries = $project->beneficiaries;
        $this->description = $project->description;
        $this->academic_year_id = $project->academic_year_id;
        $this->oldCoverImg = $project->cover_img;

        // B. JSON Fields
        $this->impact_stats = $project->impact_stats ?? [['label' => '', 'value' => '']];

        // C. Relationships (Map to array structure)

        // Objectives (FIXED: Added safety check)
        // We use the null coalescing operator (??) to default to an empty array if null
        $this->objectives = $project->objectives ? $project->objectives->pluck('objective')->toArray() : [];
        if(empty($this->objectives)) $this->objectives = [''];

        // SDGs
        $this->selectedSdgs = $project->sdgs ? $project->sdgs->pluck('id')->toArray() : [];

        // Proponents
        // Check if proponents exist before mapping
        if ($project->proponents) {
            $this->proponents = $project->proponents->map(function($p) {
                return [
                    'type' => $p->user_id ? 'user' : 'custom',
                    'id'   => $p->user_id,
                    'name' => $p->name 
                ];
            })->toArray();
        }
        if(empty($this->proponents)) $this->proponents = [['type' => 'user', 'id' => '', 'name' => '']];

        // Partners (Linkages)
        // Check if projectLinkages exist before mapping
        if ($project->projectLinkages) {
            $this->partners = $project->projectLinkages->map(function($l) {
                return [
                    'type' => $l->linkage_id ? 'database' : 'custom',
                    'id'   => $l->linkage_id,
                    'name' => $l->manual_name,
                    'role' => $l->role
                ];
            })->toArray();
        }
        if(empty($this->partners)) $this->partners = [['type' => 'database', 'id' => '', 'name' => '', 'role' => 'Partner']];

        // D. Load Dropdowns
        $this->users = User::orderBy('name')->get();
        $this->academic_years = AcademicYear::orderBy('is_active', 'desc')->orderBy('name', 'desc')->get();
        $this->categories = ProjectCategory::orderBy('name')->get();
        $this->availableLinkages = Linkage::orderBy('name')->get();
        $this->refreshGalleryInputs();
    }

    public function refreshGalleryInputs()
    {
        $this->galleryInputs = $this->project->galleries()
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'image_path' => $item->image_path,
                    'title' => $item->title,
                    'description' => $item->description,
                ];
            })->toArray();
    }

    public function deleteGalleryItem($galleryId)
    {
        $item = \App\Models\ProjectGallery::find($galleryId);
        
        if ($item) {
            // Optional: Delete file from storage
            if (Storage::disk('public')->exists($item->image_path)) {
                Storage::disk('public')->delete($item->image_path);
            }
            $item->delete();
        }

        $this->refreshGalleryInputs();
        session()->flash('message', 'Image removed.');
    }
    // ==========================================
    // DYNAMIC UPDATES
    // ==========================================
    public function updatedTitle($value)
    {
        // Only auto-update slug if the user hasn't saved it yet? 
        // Or strictly follow title? Usually in Edit mode, we avoid auto-changing slug 
        // unless explicitly requested to prevent breaking existing links. 
        // Comment this out if you want to protect the slug.
        // $this->slug = Str::slug($value); 
    }

    // ==========================================
    // LIST MANAGEMENT (Standard Add/Remove)
    // ==========================================
    public function addProponent() { $this->proponents[] = ['type' => 'user', 'id' => '', 'name' => '']; }
    public function removeProponent($index) { unset($this->proponents[$index]); $this->proponents = array_values($this->proponents); }

    public function addObjective() { $this->objectives[] = ''; }
    public function removeObjective($index) { unset($this->objectives[$index]); $this->objectives = array_values($this->objectives); }

    public function addPartner() { $this->partners[] = ['type' => 'database', 'id' => '', 'name' => '', 'role' => 'Partner']; }
    public function removePartner($index) { unset($this->partners[$index]); $this->partners = array_values($this->partners); }

    public function addStat() { $this->impact_stats[] = ['label' => '', 'value' => '']; }
    public function removeStat($index) { unset($this->impact_stats[$index]); $this->impact_stats = array_values($this->impact_stats); }

    public function toggleSdg($id)
    {
        if (in_array($id, $this->selectedSdgs)) {
            $this->selectedSdgs = array_diff($this->selectedSdgs, [$id]);
        } else {
            $this->selectedSdgs[] = $id;
        }
    }

    // ==========================================
    // UPDATE LOGIC
    // ==========================================
    public function update()
    {
        $this->validate([
            'title' => 'required|min:5',
            // Ignore current project ID for unique slug check
            'slug'  => ['required', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($this->project->id)],
            'date'  => 'required|date',
            'project_category_id' => 'required|exists:project_categories,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'coverImg' => 'nullable|image|max:5120',
        ]);

        DB::transaction(function () {
            
            // A. Handle Image (Only update if new one is uploaded)
            $imagePath = $this->oldCoverImg;
            if ($this->coverImg) {
                // Optional: Delete old image if exists
                if ($this->oldCoverImg && Storage::disk('public')->exists($this->oldCoverImg)) {
                    Storage::disk('public')->delete($this->oldCoverImg);
                }
                $imagePath = $this->coverImg->store('projects', 'public');
            }

            // B. Update Main Record
            $this->project->update([
                'title' => $this->title,
                'slug' => $this->slug,
                'academic_year_id' => $this->academic_year_id,
                'project_category_id' => $this->project_category_id,
                'status' => $this->status,
                'implementation_date' => $this->date,
                'location' => $this->location,
                'beneficiaries' => $this->beneficiaries,
                'description' => $this->description,
                'cover_img' => $imagePath,
                'impact_stats' => array_filter($this->impact_stats, fn($i) => !empty($i['value'])),
            ]);

            foreach ($this->galleryInputs as $input) {
                \App\Models\ProjectGallery::where('id', $input['id'])->update([
                    'title' => $input['title'],
                    'description' => $input['description'],
                ]);
            }

            // B. Save NEW Images
            foreach ($this->newGalleryImages as $photo) {
                $path = $photo->store('projects/gallery', 'public');
                
                \App\Models\ProjectGallery::create([
                    'project_id' => $this->project->id,
                    'image_path' => $path,
                    'title' => '',       // Default empty, they can edit later
                    'description' => '', 
                ]);
            }
            // C. Refresh Relationships (Delete & Re-create Strategy)
            
            // 1. Linkages
            $this->project->projectLinkages()->delete(); // Clear old
            foreach ($this->partners as $partner) {
                if (empty($partner['id']) && empty($partner['name'])) continue;
                LinkageProject::create([
                    'project_id'  => $this->project->id,
                    'role'        => $partner['role'] ?? 'Partner',
                    'linkage_id'  => ($partner['type'] === 'database' && !empty($partner['id'])) ? $partner['id'] : null,
                    'manual_name' => ($partner['type'] === 'custom' && !empty($partner['name'])) ? $partner['name'] : null,
                ]);
            }

            // 2. Proponents
            $this->project->proponents()->delete(); // Clear old
            foreach ($this->proponents as $prop) {
                $finalName = '';
                $finalUserId = null;
                if ($prop['type'] === 'user' && !empty($prop['id'])) {
                    $user = User::find($prop['id']);
                    if ($user) { $finalName = $user->name; $finalUserId = $user->id; }
                } elseif (!empty($prop['name'])) {
                    $finalName = $prop['name'];
                }

                if (!empty($finalName)) {
                    ProjectProponent::create([
                        'project_id' => $this->project->id,
                        'user_id'    => $finalUserId,
                        'name'       => $finalName,
                        'type'       => 'Lead' 
                    ]);
                }
            }

            // 3. Objectives
            $this->project->objectives()->delete(); // Clear old
            foreach ($this->objectives as $obj) {
                if (!empty($obj)) {
                    ProjectObjective::create([
                        'project_id' => $this->project->id,
                        'objective' => $obj
                    ]);
                }
            }

            // 4. SDGs (Sync is easier for ManyToMany)
            $this->project->sdgs()->sync($this->selectedSdgs);
        });

        $this->newGalleryImages = [];

        session()->flash('message', 'Project successfully updated!');
        return redirect()->route('projects.index');
    }

    public function render()
    {
        return view('livewire.director.projects-edit', [
            'sdgs' => Sdg::orderBy('number')->get() 
        ]);
    }
}