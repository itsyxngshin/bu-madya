<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Proposal;
use App\Models\ProposalObjective;
use App\Models\College; // Assuming you have this
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class ProposalsCreate extends Component
{
    // 1. Identity
    public $name = '';
    public $email = '';
    public $proponent_type = 'BU Student';
    public $college_id = '';

    // 2. Concept
    public $title = '';
    public $project_type = '';
    public $rationale = '';
    public $objectives = ['']; // Start with one empty row

    // 3. Logistics
    public $modality = 'onsite';
    public $venue = '';
    public $target_start_date = '';
    public $target_end_date = '';
    public $target_beneficiaries = '';
    
    // 4. Financials
    public $estimated_budget = '';
    public $potential_partners = '';

    // Dropdown Data
    public $colleges = [];

    public function mount()
    {
        $this->colleges = College::orderBy('name')->get();

        // If logged in, we don't strictly need to fill name/email 
        // because we link via user_id, but we can pre-fill for UI if needed.
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }
    }

    // Dynamic List Logic
    public function addObjective()
    {
        $this->objectives[] = '';
    }

    public function removeObjective($index)
    {
        unset($this->objectives[$index]);
        $this->objectives = array_values($this->objectives);
    }

    public function save()
    {
        // 1. Dynamic Validation Rules
        $rules = [
            'title' => 'required|min:5',
            'proponent_type' => 'required',
            'rationale' => 'required|min:20',
            'project_type' => 'required',
            'target_start_date' => 'required|date',
            'target_end_date' => 'required|date|after_or_equal:target_start_date',
            'estimated_budget' => 'nullable|numeric',
            // Validate array of objectives
            'objectives.0' => 'required|min:5', // At least one objective required
            'objectives.*' => 'nullable|string',
        ];

        // Add Name/Email validation ONLY if guest
        if (!Auth::check()) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email';
        }

        $this->validate($rules);

        DB::transaction(function () {
            // A. Create Proposal
            $proposal = Proposal::create([
                'user_id' => Auth::id(), // Null if guest
                'name'    => Auth::check() ? Auth::user()->name : $this->name,
                'email'   => Auth::check() ? Auth::user()->email : $this->email,
                'college_id' => $this->college_id ?: null,
                'proponent_type' => $this->proponent_type,
                'status'  => 'pending review', // Default status on submit
                
                'title' => $this->title,
                'project_type' => $this->project_type,
                'rationale' => $this->rationale,
                'potential_partners' => $this->potential_partners,
                
                'modality' => $this->modality,
                'venue' => $this->venue,
                'target_start_date' => $this->target_start_date,
                'target_end_date' => $this->target_end_date,
                'target_beneficiaries' => $this->target_beneficiaries,
                'estimated_budget' => $this->estimated_budget ?: 0,
            ]);

            // B. Save Objectives
            foreach ($this->objectives as $obj) {
                if (!empty(trim($obj))) {
                    ProposalObjective::create([
                        'proposal_id' => $proposal->id,
                        'objective' => $obj
                    ]);
                }
            }
        });

        // Redirect with success
        session()->flash('message', 'Proposal successfully submitted! We will contact you shortly.');
        return redirect()->route('open.home'); // Or wherever you want them to go
    }

    public function render()
    {
        return view('livewire.open.proposals-create');
    }
}