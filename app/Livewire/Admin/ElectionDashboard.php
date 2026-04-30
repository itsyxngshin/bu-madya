<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\College;
use App\Models\CandidatePlatform;
use App\Models\CandidateCredential;
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
    public $editPartyId = null; // ADDED: To hold the selected Political Party
    public $editPlatforms = [];
    public $editCredentials = [];

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

    // --- ENHANCED EDITING METHODS ---

    public function openEditModal($candidateId)
    {
        $candidate = Candidate::with(['platforms', 'credentials'])->findOrFail($candidateId);
        $this->candidateToEdit = $candidateId;
        $this->editProgram = $candidate->program;
        $this->editYearLevel = $candidate->year_level;
        $this->editPartyId = $candidate->election_party_id; // ADDED: Load existing party

        // Load existing platforms
        $this->editPlatforms = $candidate->platforms->map(function ($platform) {
            return ['id' => $platform->id, 'title' => $platform->title, 'description' => $platform->description];
        })->toArray();

        // Load existing credentials
        $this->editCredentials = $candidate->credentials->map(function ($credential) {
            return ['id' => $credential->id, 'type' => $credential->type, 'description' => $credential->description];
        })->toArray();

        $this->resetErrorBag();
    }

    public function addEditPlatform() { $this->editPlatforms[] = ['id' => null, 'title' => '', 'description' => '']; }
    public function removeEditPlatform($index) { unset($this->editPlatforms[$index]); $this->editPlatforms = array_values($this->editPlatforms); }

    public function addEditCredential() { $this->editCredentials[] = ['id' => null, 'type' => '', 'description' => '']; }
    public function removeEditCredential($index) { unset($this->editCredentials[$index]); $this->editCredentials = array_values($this->editCredentials); }

    public function saveEdit()
    {
        $this->validate([
            'editProgram' => 'required|string|max:255',
            'editYearLevel' => 'required|string|max:50',
            'editPartyId' => 'nullable|exists:election_parties,id', // ADDED: Validate the party selection
            'editPlatforms.*.title' => 'required|string|max:255',
            'editPlatforms.*.description' => 'required|string',
            'editCredentials.*.type' => 'required|string|max:100',
            'editCredentials.*.description' => 'required|string',
        ], [
            'editPlatforms.*.title.required' => 'Platform title is required.',
            'editPlatforms.*.description.required' => 'Platform description is required.',
        ]);

        DB::transaction(function () {
            $candidate = Candidate::findOrFail($this->candidateToEdit);

            // Update Basic Info & Party Affiliation
            $candidate->update([
                'program' => $this->editProgram,
                'year_level' => $this->editYearLevel,
                'election_party_id' => $this->editPartyId ?: null // ADDED: Save party or null for Independent
            ]);

            // Sync Platforms
            $savedPlatformIds = [];
            foreach ($this->editPlatforms as $platform) {
                if (isset($platform['id']) && $platform['id'] !== null) {
                    $existing = CandidatePlatform::find($platform['id']);
                    if ($existing) {
                        $existing->update(['title' => $platform['title'], 'description' => $platform['description']]);
                        $savedPlatformIds[] = $existing->id;
                    }
                } else {
                    $new = CandidatePlatform::create([
                        'candidate_id' => $candidate->id,
                        'title' => $platform['title'],
                        'description' => $platform['description']
                    ]);
                    $savedPlatformIds[] = $new->id;
                }
            }
            CandidatePlatform::where('candidate_id', $candidate->id)->whereNotIn('id', $savedPlatformIds)->delete();

            // Sync Credentials
            $savedCredentialIds = [];
            foreach ($this->editCredentials as $credential) {
                if (isset($credential['id']) && $credential['id'] !== null) {
                    $existing = CandidateCredential::find($credential['id']);
                    if ($existing) {
                        $existing->update(['type' => $credential['type'], 'description' => $credential['description']]);
                        $savedCredentialIds[] = $existing->id;
                    }
                } else {
                    $new = CandidateCredential::create([
                        'candidate_id' => $candidate->id,
                        'type' => $credential['type'],
                        'description' => $credential['description']
                    ]);
                    $savedCredentialIds[] = $new->id;
                }
            }
            CandidateCredential::where('candidate_id', $candidate->id)->whereNotIn('id', $savedCredentialIds)->delete();
        });

        $this->candidateToEdit = null;
        session()->flash('success', 'Candidate details, party affiliation, platforms, and credentials updated successfully.');
    }

    public function render()
    {
        // ADDED: Eager load 'party' relation
        $candidates = Candidate::with(['user', 'position', 'college', 'party'])
            ->where('election_id', $this->election->id)
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->get();

        return view('livewire.admin.election-dashboard', [
            'candidates' => $candidates,
            'positions' => $this->election->positions,
            'colleges' => College::orderBy('name')->get(),
            'parties' => $this->election->parties // ADDED: Pass the available parties to the frontend edit modal
        ])->layout('layouts.madya-admin-deck');
    }
}
