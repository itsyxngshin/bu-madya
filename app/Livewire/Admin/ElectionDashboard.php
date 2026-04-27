<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Election;
use App\Models\Candidate;

class ElectionDashboard extends Component
{
    public Election $election; 
    
    // Rejection State
    public $candidateToReject = null;
    public $rejectRemarks = '';

    // Edit State
    public $candidateToEdit = null;
    public $editProgram = '';
    public $editYearLevel = '';

    public function mount(Election $election)
    {
        if (auth()->user()->role?->role_name !== 'administrator' && $election->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->election = $election;
    }

    // --- VETTING ACTIONS ---

    public function approveCandidate($candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);
        
        $candidate->update([
            'status' => 'approved',
            'remarks' => null // Clear any previous rejection remarks
        ]);

        session()->flash('success', 'Candidate ' . $candidate->user->name . ' has been officially APPROVED.');
    }

    public function confirmRejection($candidateId)
    {
        $this->candidateToReject = $candidateId;
        $this->rejectRemarks = ''; // Reset the textarea
        $this->resetErrorBag();
    }

    public function rejectCandidate()
    {
        $this->validate([
            'rejectRemarks' => 'required|string|min:5|max:500'
        ], [
            'rejectRemarks.required' => 'You must provide a reason for rejection.'
        ]);

        $candidate = Candidate::findOrFail($this->candidateToReject);
        
        $candidate->update([
            'status' => 'rejected',
            'remarks' => $this->rejectRemarks // Ensure you have a 'remarks' nullable text column in your candidates table!
        ]);

        $this->candidateToReject = null;
        session()->flash('success', 'Candidate ' . $candidate->user->name . ' has been REJECTED.');
    }

    // --- EDIT ACTIONS ---

    public function openEditModal($candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);
        $this->candidateToEdit = $candidateId;
        $this->editProgram = $candidate->program;
        $this->editYearLevel = $candidate->year_level;
        $this->resetErrorBag();
    }

    public function saveEdit()
    {
        $this->validate([
            'editProgram' => 'required|string|max:255',
            'editYearLevel' => 'required|string|max:50',
        ]);

        $candidate = Candidate::findOrFail($this->candidateToEdit);
        
        $candidate->update([
            'program' => $this->editProgram,
            'year_level' => $this->editYearLevel,
        ]);

        $this->candidateToEdit = null;
        session()->flash('success', 'Candidate details successfully updated.');
    }

    public function render()
    {
        $candidates = Candidate::with(['user', 'position', 'college'])
            ->where('election_id', $this->election->id)
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->get();

        return view('livewire.admin.election-dashboard', [
            'candidates' => $candidates
        ])->layout('layouts.madya-admin-deck');
    }
}