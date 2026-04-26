<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\College;
use App\Models\CandidatePlatform;
use App\Models\CandidateCredential;
use Illuminate\Support\Facades\DB;

class CandidateApplicationForm extends Component
{
    use WithFileUploads;

    public $hasApplied = false;

    // Dropdown Data
    public $activeElections = [];
    public $availablePositions = [];
    public $colleges = [];

    // Form Fields - Basic Info
    public $election_id = '';
    public $election_position_id = '';
    public $college_id = '';
    public $program = '';
    public $year_level = '';
    public $address = '';

    // Form Fields - Files
    public $profile_photo;
    public $e_signature;

    // Form Fields - Dynamic Arrays
    public $platforms = [];
    public $credentials = [];

    public function mount()
    {
        // 1. Fetch only elections currently accepting applications
        $this->activeElections = Election::where('status', 'active')
            ->where('application_start', '<=', now())
            ->where('application_end', '>=', now())
            ->get();

        $this->colleges = College::orderBy('name')->get();

        // 2. Initialize the dynamic arrays with one empty row each
        $this->platforms[] = ['title' => '', 'description' => ''];
        $this->credentials[] = ['type' => '', 'description' => ''];

        // 3. Auto-select if there is only 1 active election
        if ($this->activeElections->count() === 1) {
            $this->election_id = $this->activeElections->first()->id;
            $this->updatedElectionId($this->election_id);
        }
    }

    // Magic Method: Triggers automatically when $election_id changes in the UI
    public function updatedElectionId($value)
    {
        if ($value) {
            $election = Election::with('positions')->find($value);
            $this->availablePositions = $election ? $election->positions : [];
            
            // Check if they already applied for THIS specific election
            if (auth()->check()) {
                $this->hasApplied = Candidate::where('user_id', auth()->id())
                                             ->where('election_id', $value)
                                             ->exists();
            }
        } else {
            $this->availablePositions = [];
            $this->hasApplied = false;
        }
    }

    // --- PLATFORM ARRAY MANAGEMENT ---
    public function addPlatform()
    {
        $this->platforms[] = ['title' => '', 'description' => ''];
    }

    public function removePlatform($index)
    {
        if (count($this->platforms) > 1) {
            unset($this->platforms[$index]);
            $this->platforms = array_values($this->platforms); // Re-index array
        }
    }

    // --- CREDENTIAL ARRAY MANAGEMENT ---
    public function addCredential()
    {
        $this->credentials[] = ['type' => '', 'description' => ''];
    }

    public function removeCredential($index)
    {
        if (count($this->credentials) > 1) {
            unset($this->credentials[$index]);
            $this->credentials = array_values($this->credentials); // Re-index array
        }
    }

    // --- FINAL SUBMISSION ---
    public function submitApplication()
    {
        // 1. Strict Validation
        $this->validate([
            'election_id' => 'required|exists:elections,id',
            'election_position_id' => 'required|exists:election_positions,id',
            'college_id' => 'required|exists:colleges,id',
            'program' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'address' => 'required|string',
            'profile_photo' => 'required|image|max:2048', // Max 2MB
            'e_signature' => 'required|image|max:2048',
            
            // Validate the dynamic arrays
            'platforms.*.title' => 'required|string|max:255',
            'platforms.*.description' => 'required|string',
            'credentials.*.type' => 'required|string|max:50',
            'credentials.*.description' => 'required|string',
        ], [
            'election_id.required' => 'Please select an election.',
            'election_position_id.required' => 'Please select a position.',
            'platforms.*.title.required' => 'Platform title is required.',
            'platforms.*.description.required' => 'Platform description is required.',
            'credentials.*.type.required' => 'Credential type is required.',
            'credentials.*.description.required' => 'Credential description is required.',
        ]);

        // 2. Database Transaction to ensure all data saves together
        DB::transaction(function () {
            
            // A. Store the files
            $photoPath = $this->profile_photo->store('candidates/photos', 'public');
            $signaturePath = $this->e_signature->store('candidates/signatures', 'public');

            // B. Create the core Candidate profile
            $candidate = Candidate::create([
                'user_id' => auth()->id(),
                'election_id' => $this->election_id,
                'election_position_id' => $this->election_position_id,
                'college_id' => $this->college_id,
                'program' => $this->program,
                'year_level' => $this->year_level,
                'address' => $this->address,
                'profile_photo_path' => $photoPath,
                'e_signature_path' => $signaturePath,
                'status' => 'pending', // Starts as pending for vetting
            ]);

            // C. Save all Platforms
            foreach ($this->platforms as $platform) {
                CandidatePlatform::create([
                    'candidate_id' => $candidate->id,
                    'title' => $platform['title'],
                    'description' => $platform['description'],
                ]);
            }

            // D. Save all Credentials
            foreach ($this->credentials as $credential) {
                CandidateCredential::create([
                    'candidate_id' => $candidate->id,
                    'type' => $credential['type'],
                    'description' => $credential['description'],
                ]);
            }
        });

        // 3. Update UI state and flash success
        $this->hasApplied = true;
        session()->flash('success', 'Your candidacy has been submitted to the Electoral Board!');
    }

    public function render()
    {
        return view('livewire.open.candidate-application-form')
            ->layout('layouts.madya-template'); // Update layout name if yours is different
    }
}