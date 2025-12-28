<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Carbon\Carbon;

// Models
use App\Models\Linkage;
use App\Models\LinkageType;
use App\Models\LinkageStatus;
use App\Models\AgreementLevel;
use App\Models\LinkageActivity; // To log activities
use App\Models\Sdg; // Assuming you have an SDG model
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class LinkagesRoster extends Component
{
    use WithPagination;
    use WithFileUploads;

    // -- Filters --
    public $search = '';
    public $typeFilter = '';
    public $statusFilter = '';

    // -- Modal States --
    public $isCreateModalOpen = false;
    public $isDetailModalOpen = false;

    // -- Form Data --
    public $linkageId;
    public $form = [
        'name' => '', 'acronym' => '', 'email' => '', 'website' => '',
        'linkage_type_id' => '', 'linkage_status_id' => '', 'agreement_level_id' => '',
        'established_at' => '', 'expires_at' => '',
        'description' => '', 'address' => '', 'contact_person' => ''
    ];
    public $logo; 
    public $selectedSdgs = []; // IDs of selected SDGs

    // -- Detail View Data --
    public $viewingLinkage;
    
    // -- Activity Form (Inside Detail View) --
    public $newActivity = ['title' => '', 'activity_date' => '', 'description' => ''];

    // -- COMPUTED PROPERTIES (For Dropdowns) --
    public function getTypesProperty() { return LinkageType::all(); }
    public function getStatusesProperty() { return LinkageStatus::all(); }
    public function getAgreementsProperty() { return AgreementLevel::all(); }
    public function getSdgsProperty() { return Sdg::all(); } // Assuming Sdg model exists

    public function render()
    {
        $linkages = Linkage::query()
            ->with(['type', 'status', 'agreementLevel'])
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('acronym', 'like', '%'.$this->search.'%');
            })
            ->when($this->typeFilter, fn($q) => $q->where('linkage_type_id', $this->typeFilter))
            ->when($this->statusFilter, fn($q) => $q->where('linkage_status_id', $this->statusFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.linkages-roster', [
            'linkages' => $linkages
        ]);
    }

    // -- CREATE / EDIT --
    public function create()
    {
        $this->reset(['form', 'linkageId', 'logo', 'selectedSdgs']);
        $this->isCreateModalOpen = true;
    }

    public function edit($id)
    {
        $linkage = Linkage::with('sdgs')->find($id);
        $this->linkageId = $id;
        $this->form = $linkage->only([
            'name', 'acronym', 'email', 'website', 'linkage_type_id', 
            'linkage_status_id', 'agreement_level_id', 'description', 
            'address', 'contact_person'
        ]);
        // Date formatting for input
        $this->form['established_at'] = optional($linkage->established_at)->format('Y-m-d');
        $this->form['expires_at'] = optional($linkage->expires_at)->format('Y-m-d');
        
        $this->selectedSdgs = $linkage->sdgs->pluck('id')->toArray();
        $this->isCreateModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'form.name' => 'required|string',
            'form.linkage_type_id' => 'required|exists:linkage_types,id',
            'form.linkage_status_id' => 'required|exists:linkage_statuses,id',
            'logo' => 'nullable|image|max:1024',
        ]);

        $data = $this->form;
        $data['slug'] = Str::slug($data['name']);

        // Handle Logo Upload
        if ($this->logo) {
            $data['logo_path'] = $this->logo->store('linkage-logos', 'public');
        }

        if ($this->linkageId) {
            $linkage = Linkage::find($this->linkageId);
            $linkage->update($data);
        } else {
            $linkage = Linkage::create($data);
        }

        // Sync SDGs
        if (!empty($this->selectedSdgs)) {
            $linkage->sdgs()->sync($this->selectedSdgs);
        }

        session()->flash('message', 'Linkage saved successfully.');
        $this->isCreateModalOpen = false;
        $this->reset(['form', 'linkageId', 'logo', 'selectedSdgs']);
    }

    // -- DETAIL VIEW --
    public function viewDetails($id)
    {
        $this->viewingLinkage = Linkage::with([
            'type', 'status', 'agreementLevel', 'sdgs',
            'projects', // Linked Projects
            'activities' => fn($q) => $q->orderBy('activity_date', 'desc') // Activities History
        ])->find($id);

        $this->isDetailModalOpen = true;
    }

    // -- ADD ACTIVITY LOGIC --
    public function saveActivity()
    {
        $this->validate([
            'newActivity.title' => 'required|string',
            'newActivity.activity_date' => 'required|date',
        ]);

        LinkageActivity::create([
            'linkage_id' => $this->viewingLinkage->id,
            'title' => $this->newActivity['title'],
            'activity_date' => $this->newActivity['activity_date'],
            'description' => $this->newActivity['description'],
        ]);

        session()->flash('activity_message', 'Activity logged.');
        $this->reset('newActivity');
        $this->viewDetails($this->viewingLinkage->id); // Refresh
    }

    public function delete($id)
    {
        Linkage::find($id)->delete();
        session()->flash('message', 'Linkage deleted.');
    }
}