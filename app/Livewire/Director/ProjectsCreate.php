<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\ProjectCategory; // Assuming you have this
use App\Models\ProjectObjective;
use App\Models\Sdg;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class ProjectsCreate extends Component
{
    // 1. Basic Fields
    public $title = '';
    public $cat = 'Community Outreach'; // Default selection
    public $status = 'Upcoming';
    public $date = '';
    public $location = '';
    public $proponent = '';
    public $beneficiaries = '';
    public $coverImg = '';
    public $description = '';
    public $slug = '';

    // 2. Dynamic Lists (Arrays)
    public $objectives = ['']; // Start with one empty row
    public $partners = [''];   // Start with one empty row
    public $impact_stats = [
        ['label' => '', 'value' => '']
    ]; 

    // 3. SDGs
    public $selectedSdgs = [];
    
    
    // Static SDG Data for the UI (Colors/Labels)

    // ==========================================
    // DYNAMIC LIST MANAGEMENT
    // ==========================================

    public function updatedTitle($value)
    {
        // Only auto-fill if the user hasn't manually entered a complex slug yet
        // Or simply always update it for convenience until they save
        $this->slug = Str::slug($value); 
    }

    public function addObjective()
    {
        $this->objectives[] = '';
    }

    public function removeObjective($index)
    {
        unset($this->objectives[$index]);
        $this->objectives = array_values($this->objectives); // Re-index array
    }

    public function addPartner()
    {
        $this->partners[] = '';
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
            'date' => 'required',
            'cat' => 'required',
        ]);

        DB::transaction(function () {
            // 1. Handle Category Logic (Find ID based on Name, or create if strictly needed)
            // Assuming you have a ProjectCategory model seeded with these names
            $category = ProjectCategory::firstOrCreate(['name' => $this->cat]);

            // 2. Create the Main Project
            $project = Project::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'project_category_id' => $category->id,
                'status' => $this->status,
                'implementation_date' => $this->date, // Ensure Model casts this to date
                'location' => $this->location,
                'beneficiaries' => $this->beneficiaries,
                'proponent_text' => $this->proponent, // Storing as text for now
                'description' => $this->description,
                'cover_img' => $this->coverImg,
                
                // Saving JSON columns directly
                'partners_list' => array_filter($this->partners), // Remove empty strings
                'impact_stats' => array_filter($this->impact_stats, fn($i) => !empty($i['value'])),
            ]);

            // 3. Save Objectives (HasMany)
            foreach ($this->objectives as $obj) {
                if (!empty($obj)) {
                    ProjectObjective::create([
                        'project_id' => $project->id,
                        'objective' => $obj
                    ]);
                }
            }

            // 4. Save SDGs (BelongsToMany)
            // Make sure your Project model has: return $this->belongsToMany(Sdg::class, 'project_sdgs');
            if (!empty($this->selectedSdgs)) {
                $project->sdgs()->attach($this->selectedSdgs);
            }
        });

        // Redirect
        session()->flash('message', 'Project successfully created!');
        return redirect()->route('projects.index');
    }

    public function render()
    {
        return view('livewire.director.projects-create', [
            // Order by number so they appear 1-17
            'sdgs' => Sdg::orderBy('number')->get() 
        ]);
    }
}