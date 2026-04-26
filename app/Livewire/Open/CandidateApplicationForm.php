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

    public Election $election; // Bound securely via URL slug
    public $hasApplied = false;
    public $isApplicationOpen = false;

    // Dropdown Data
    public $availablePositions = [];
    public $colleges = [];

    // Form Fields
    public $election_position_id = '';
    public $college_id = '';
    public $program = '';
    public $year_level = '';
    public $address = '';
    public $profile_photo;
    public $e_signature;

    // Dynamic Arrays (Using uniqid keys for Livewire DOM morphing)
    public $platforms = [];
    public $credentials = [];

    public function mount(Election $election)
    {
        $this->election = $election;

        // 1. Check Timeline Safety Lock
        $now = now();
        $this->isApplicationOpen = ($this->election->status === 'active' && 
                                    $now >= $this->election->application_start && 
                                    $now <= $this->election->application_end);

        // 2. Fetch Data
        $this->availablePositions = $this->election->positions;
        $this->colleges = College::orderBy('name')->get();

        // 3. Initialize arrays with unique keys
        $this->platforms[] = ['key' => uniqid(), 'title' => '', 'description' => ''];
        $this->credentials[] = ['key' => uniqid(), 'type' => '', 'description' => ''];

        // 4. Check if already applied to prevent duplicate submissions
        if (auth()->check()) {
            $this->hasApplied = Candidate::where('user_id', auth()->id())
                                         ->where('election_id', $this->election->id)
                                         ->exists();
        }
    }

    // --- PLATFORM MANAGEMENT ---
    public function addPlatform() 
    { 
        $this->platforms[] = ['key' => uniqid(), 'title' => '', 'description' => '']; 
    }
    
    public function removePlatform($index) 
    {
        if (count($this->platforms) > 1) {
            unset($this->platforms[$index]);
            $this->platforms = array_values($this->platforms); // Re-index array
        }
    }

    // --- CREDENTIAL MANAGEMENT ---
    public function addCredential() 
    { 
        $this->credentials[] = ['key' => uniqid(), 'type' => '', 'description' => '']; 
    }
    
    public function removeCredential($index) 
    {
        if (count($this->credentials) > 1) {
            unset($this->credentials[$index]);
            $this->credentials = array_values($this->credentials); // Re-index array
        }
    }

    // --- SUBMISSION ---
    public function submitApplication()
    {
        // Ultimate security check to ensure they didn't bypass the UI
        if (!$this->isApplicationOpen) {
            session()->flash('error', 'The application window for this election is closed.');
            return;
        }

        $this->validate([
            'election_position_id' => 'required|exists:election_positions,id',
            'college_id' => 'required|exists:colleges,id',
            'program' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'address' => 'required|string',
            'profile_photo' => 'required|image|max:2048', // 2MB Max
            'e_signature' => 'required|image|max:2048', // 2MB Max
            
            // Validate array contents (Livewire safely ignores the 'key')
            'platforms.*.title' => 'required|string|max:255',
            'platforms.*.description' => 'required|string',
            'credentials.*.type' => 'required|string|max:50',
            'credentials.*.description' => 'required|string',
        ], [
            'election_position_id.required' => 'Please select a position.',
            'platforms.*.title.required' => 'Platform title is required.',
            'platforms.*.description.required' => 'Platform description is required.',
            'credentials.*.type.required' => 'Credential type is required.',
            'credentials.*.description.required' => 'Credential description is required.',
        ]);

        DB::transaction(function () {
            // Save Images
            $photoPath = $this->profile_photo->store('candidates/photos', 'public');
            $signaturePath = $this->e_signature->store('candidates/signatures', 'public');

            // Create the Master Candidate Record
            $candidate = Candidate::create([
                'user_id' => auth()->id(),
                'election_id' => $this->election->id,
                'election_position_id' => $this->election_position_id,
                'college_id' => $this->college_id,
                'program' => $this->program,
                'year_level' => $this->year_level,
                'address' => $this->address,
                'profile_photo_path' => $photoPath,
                'e_signature_path' => $signaturePath,
                'status' => 'pending', // Awaits Electoral Board vetting
            ]);

            // Save Dynamic Platforms
            foreach ($this->platforms as $platform) {
                CandidatePlatform::create([
                    'candidate_id' => $candidate->id, 
                    'title' => $platform['title'], 
                    'description' => $platform['description']
                ]);
            }

            // Save Dynamic Credentials
            foreach ($this->credentials as $credential) {
                CandidateCredential::create([
                    'candidate_id' => $candidate->id, 
                    'type' => $credential['type'], 
                    'description' => $credential['description']
                ]);
            }
        });

        // Trigger Success UI
        $this->hasApplied = true;
        session()->flash('success', 'Your candidacy has been submitted to the Electoral Board!');
    }

    public function render()
    {
        return view('livewire.open.candidate-application-form')
            ->layout('layouts.madya-template'); // Change to layouts.madya-template if needed
    }
}