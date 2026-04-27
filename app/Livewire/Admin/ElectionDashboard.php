<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads; // IMPORT THE FILE UPLOAD TRAIT
use App\Models\Election;
use App\Models\Candidate;
use App\Models\User;
use App\Models\College;
use Illuminate\Support\Str;

class ElectionDashboard extends Component
{
    use WithFileUploads; // ACTIVATE THE TRAIT

    public Election $election; 
    
    // Rejection State
    public $candidateToReject = null;
    public $rejectRemarks = '';

    // Edit State
    public $candidateToEdit = null;
    public $editProgram = '';
    public $editYearLevel = '';

    // Test Candidate State
    public $showTestModal = false;
    public $testName = '';
    public $testPhoto; // NEW: Photo Property
    public $testPositionId = '';
    public $testCollegeId = '';
    public $testProgram = '';
    public $testYearLevel = '';

    public function mount(Election $election)
    {
        if (auth()->user()->role?->role_name !== 'administrator' && $election->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->election = $election;
    }

    // --- TEST CANDIDATE GENERATOR ---

    public function createTestCandidate()
    {
        $this->validate([
            'testName' => 'required|string|max:255',
            'testPhoto' => 'nullable|image|max:2048', // NEW: Validate the image (Max 2MB)
            'testPositionId' => 'required|exists:election_positions,id',
            'testCollegeId' => 'required|exists:colleges,id',
            'testProgram' => 'required|string|max:255',
            'testYearLevel' => 'required|string|max:50',
        ]);

        // Process the photo if one was uploaded
        $photoPath = null;
        if ($this->testPhoto) {
            // Saves to storage/app/public/profile-photos
            $photoPath = $this->testPhoto->store('profile-photos', 'public');
        }

        // Create a dummy user so foreign keys don't break
        $dummyUser = User::create([
            'name' => $this->testName,
            'email' => 'test_' . Str::random(8) . '@example.com',
            'password' => bcrypt(Str::random(16)), 
        ]);

        // Create the auto-approved candidate
        Candidate::create([
            'election_id' => $this->election->id,
            'election_position_id' => $this->testPositionId,
            'user_id' => $dummyUser->id,
            'college_id' => $this->testCollegeId,
            'display_name' => $this->testName, 
            'profile_photo_path' => $photoPath, // NEW: Save the photo path
            'program' => $this->testProgram,
            'year_level' => $this->testYearLevel,
            'status' => 'approved', 
        ]);

        $this->showTestModal = false;
        $this->reset(['testName', 'testPhoto', 'testPositionId', 'testCollegeId', 'testProgram', 'testYearLevel']);
        session()->flash('success', 'Test Candidate successfully generated and added to the ballot.');
    }

    // --- VETTING ACTIONS ---

    public function approveCandidate($candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);
        
        $candidate->update([
            'status' => 'approved',
            'remarks' => null
        ]);

        session()->flash('success', 'Candidate ' . ($candidate->display_name ?? $candidate->user->name) . ' has been officially APPROVED.');
    }

    public function confirmRejection($candidateId)
    {
        $this->candidateToReject = $candidateId;
        $this->rejectRemarks = ''; 
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
            'remarks' => $this->rejectRemarks
        ]);

        $this->candidateToReject = null;
        session()->flash('success', 'Candidate ' . ($candidate->display_name ?? $candidate->user->name) . ' has been REJECTED.');
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
            'candidates' => $candidates,
            'positions' => $this->election->positions,
            'colleges' => College::orderBy('name')->get()
        ])->layout('layouts.madya-admin-deck');
    }
}