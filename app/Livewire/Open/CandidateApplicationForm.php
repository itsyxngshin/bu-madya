<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\CandidatePlatform;
use App\Models\CandidateCredential;
use App\Models\College;
use Illuminate\Support\Facades\DB;

class CandidateApplicationForm extends Component
{
    use WithFileUploads;

    public Election $election;
    public $hasApplied = false;

    // Basic Info
    public $display_name = '';
    public $profile_photo;
    public $election_position_id = '';
    public $college_id = '';
    public $program = '';
    public $year_level = '';

    // Dynamic Arrays for GPOA and Credentials
    public $platforms = [
        ['title' => '', 'description' => '']
    ];
    
    public $credentials = [];

    public function mount(Election $election)
    {
        $this->election = $election;

        if (!auth()->check()) {
            abort(403, 'You must be logged in to apply.');
        }

        // Pre-fill the display name with their registered account name
        $this->display_name = auth()->user()->name;

        // Check if they already applied
        $existingCandidate = Candidate::where('election_id', $this->election->id)
                                      ->where('user_id', auth()->id())
                                      ->first();

        if ($existingCandidate) {
            $this->hasApplied = true;
        }
    }

    // --- DYNAMIC FIELD METHODS ---

    public function addPlatform()
    {
        $this->platforms[] = ['title' => '', 'description' => ''];
    }

    public function removePlatform($index)
    {
        unset($this->platforms[$index]);
        $this->platforms = array_values($this->platforms);
    }

    public function addCredential()
    {
        $this->credentials[] = ['type' => '', 'description' => ''];
    }

    public function removeCredential($index)
    {
        unset($this->credentials[$index]);
        $this->credentials = array_values($this->credentials);
    }

    // --- SUBMISSION LOGIC ---

    public function submitApplication()
    {
        // 1. Validate Everything
        $this->validate([
            'display_name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image', // 2MB Max
            'election_position_id' => 'required|exists:election_positions,id',
            'college_id' => 'required|exists:colleges,id',
            'program' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            
            // Validate the dynamic arrays
            'platforms.*.title' => 'required|string|max:255',
            'platforms.*.description' => 'required|string',
            'credentials.*.type' => 'required|string|max:100',
            'credentials.*.description' => 'required|string',
        ], [
            'platforms.*.title.required' => 'Platform title is required.',
            'platforms.*.description.required' => 'Platform description is required.',
            'credentials.*.type.required' => 'Credential type is required.',
            'credentials.*.description.required' => 'Credential description is required.',
        ]);

        // 2. Process Photo Upload
        $photoPath = null;
        if ($this->profile_photo) {
            $photoPath = $this->profile_photo->store('profile-photos', 'public');
        }

        // 3. Save to Database Safely
        try {
            DB::transaction(function () use ($photoPath) {
                
                // Create the Candidate Record (Defaults to 'pending')
                $candidate = Candidate::create([
                    'election_id' => $this->election->id,
                    'user_id' => auth()->id(),
                    'election_position_id' => $this->election_position_id,
                    'college_id' => $this->college_id,
                    'display_name' => $this->display_name,
                    'profile_photo_path' => $photoPath,
                    'program' => $this->program,
                    'year_level' => $this->year_level,
                    'status' => 'pending', 
                ]);

                // Save Platforms
                foreach ($this->platforms as $platform) {
                    CandidatePlatform::create([
                        'candidate_id' => $candidate->id,
                        'title' => $platform['title'],
                        'description' => $platform['description'],
                    ]);
                }

                // Save Credentials (if any)
                foreach ($this->credentials as $credential) {
                    CandidateCredential::create([
                        'candidate_id' => $candidate->id,
                        'type' => $credential['type'],
                        'description' => $credential['description'],
                    ]);
                }
            });

            $this->hasApplied = true;
            session()->flash('success', 'Your application has been submitted and is pending review by the electoral board.');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving your application. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.open.candidate-application-form', [
            'positions' => $this->election->positions()->orderBy('order')->get(),
            'colleges' => College::orderBy('name')->get()
        ])->layout('layouts.madya-template');
    }
}