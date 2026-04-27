<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\College;
use Illuminate\Support\Facades\DB;

class ElectionDashboard extends Component
{
    use WithFileUploads;

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
    public $testPhoto;
    public $testPositionId = '';
    public $testCollegeId = '';
    public $testProgram = '';
    public $testYearLevel = '';
    public $testQuantity = 1;

    public function mount(Election $election)
    {
        if (auth()->user()->role?->role_name !== 'administrator' && $election->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        $this->election = $election;
    }

    public function createTestCandidate()
    {
        $this->validate([
            'testName' => 'required|string|max:255',
            'testPhoto' => 'nullable|image|max:2048',
            'testPositionId' => 'required|exists:election_positions,id',
            'testCollegeId' => 'required|exists:colleges,id',
            'testProgram' => 'required|string|max:255',
            'testYearLevel' => 'required|string|max:50',
            'testQuantity' => 'required|integer|min:1|max:50',
        ]);

        $photoPath = $this->testPhoto ? $this->testPhoto->store('profile-photos', 'public') : null;
        $quantityCreated = $this->testQuantity;

        DB::transaction(function () use ($photoPath, $quantityCreated) {
            for ($i = 0; $i < $quantityCreated; $i++) {
                $displayName = $quantityCreated > 1 ? $this->testName . ' #' . ($i + 1) : $this->testName;

                // THE FIX: We no longer create a dummy User!
                // user_id is explicitly set to null.
                Candidate::create([
                    'election_id' => $this->election->id,
                    'election_position_id' => $this->testPositionId,
                    'user_id' => null,
                    'college_id' => $this->testCollegeId,
                    'display_name' => $displayName,
                    'profile_photo_path' => $photoPath,
                    'program' => $this->testProgram,
                    'year_level' => $this->testYearLevel,
                    'status' => 'approved',
                ]);
            }
        });

        $this->showTestModal = false;
        $this->reset(['testName', 'testPhoto', 'testPositionId', 'testCollegeId', 'testProgram', 'testYearLevel']);
        $this->testQuantity = 1;

        session()->flash('success', $quantityCreated > 1 ? "Generated {$quantityCreated} dummy candidates!" : 'Test Candidate added to ballot.');
    }

    public function approveCandidate($candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);
        $candidate->update(['status' => 'approved', 'remarks' => null]);
        session()->flash('success', 'Candidate ' . ($candidate->display_name ?? optional($candidate->user)->name) . ' has been APPROVED.');
    }

    public function confirmRejection($candidateId)
    {
        $this->candidateToReject = $candidateId;
        $this->rejectRemarks = '';
        $this->resetErrorBag();
    }

    public function rejectCandidate()
    {
        $this->validate(['rejectRemarks' => 'required|string|min:5|max:500']);
        $candidate = Candidate::findOrFail($this->candidateToReject);
        $candidate->update(['status' => 'rejected', 'remarks' => $this->rejectRemarks]);
        $this->candidateToReject = null;
        session()->flash('success', 'Candidate REJECTED.');
    }

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
        $this->validate(['editProgram' => 'required|string|max:255', 'editYearLevel' => 'required|string|max:50']);
        Candidate::findOrFail($this->candidateToEdit)->update(['program' => $this->editProgram, 'year_level' => $this->editYearLevel]);
        $this->candidateToEdit = null;
        session()->flash('success', 'Details updated.');
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
