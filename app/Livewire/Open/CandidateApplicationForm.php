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
    public $applicationState = 'closed'; // 'upcoming', 'open', or 'closed'

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
        $this->display_name = auth()->check() ? auth()->user()->name : null;

        // 1. Check if they already applied
        $existingCandidate = Candidate::where('election_id', $this->election->id)
                                      ->where('user_id', auth()->id())
                                      ->first();

        if ($existingCandidate) {
            $this->hasApplied = true;
        }

        // 2. Determine the precise state of the application window
        $now = now();

        if ($this->election->application_start && $now < $this->election->application_start) {
            $this->applicationState = 'upcoming';
        } elseif ($this->election->application_end && $now > $this->election->application_end) {
            $this->applicationState = 'closed';
        } else {
            $this->applicationState = 'open';
        }
    }

    // --- DYNAMIC FIELD METHODS ---
    public function addPlatform() { $this->platforms[] = ['title' => '', 'description' => '']; }
    public function removePlatform($index) { unset($this->platforms[$index]); $this->platforms = array_values($this->platforms); }
    public function addCredential() { $this->credentials[] = ['type' => '', 'description' => '']; }
    public function removeCredential($index) { unset($this->credentials[$index]); $this->credentials = array_values($this->credentials); }

    // --- SUBMISSION LOGIC ---
    public function submitApplication()
    {
        // Security: Double-check the window is still open at the exact moment of submission
        if ($this->applicationState !== 'open') {
            session()->flash('error', 'The application window is currently closed.');
            return;
        }

        $this->validate([
            'display_name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image',
            'election_position_id' => 'required|exists:election_positions,id',
            'college_id' => 'required|exists:colleges,id',
            'program' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
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

        $photoPath = $this->profile_photo ? $this->profile_photo->store('profile-photos', 'public') : null;

        try {
            DB::transaction(function () use ($photoPath) {
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

                foreach ($this->platforms as $platform) {
                    CandidatePlatform::create(['candidate_id' => $candidate->id, 'title' => $platform['title'], 'description' => $platform['description']]);
                }
                foreach ($this->credentials as $credential) {
                    CandidateCredential::create(['candidate_id' => $candidate->id, 'type' => $credential['type'], 'description' => $credential['description']]);
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
