<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Election;
use App\Models\College;
use App\Models\VoterLog;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class VotingBooth extends Component
{
    // The magic fix: Livewire will automatically find the election by the URL slug!
    public Election $election; 
    
    public $isVotingOpen = false;
    public $hasVoted = false;

    // Ballot Data
    public $positions;
    
    // This will hold the user's choices. Format: [position_id => [candidate_id1, candidate_id2]]
    public $selectedCandidates = []; 

    // Guest Voter Form (Used if user is not logged in)
    public $colleges = [];
    public $guest_name;
    public $guest_email;
    public $college_id;
    public $program;
    public $year_level;

    public function mount(Election $election)
    {
        $this->election = $election;

        // 1. Check if the voting window is currently open
        $now = now();
        $this->isVotingOpen = (
            $this->election->status === 'active' &&
            $this->election->voting_start &&
            $this->election->voting_end &&
            $now->between($this->election->voting_start, $this->election->voting_end)
        );

        // 2. Check if an Authenticated User has already voted
        if (auth()->check()) {
            $this->hasVoted = VoterLog::where('user_id', auth()->id())
                                      ->where('election_id', $this->election->id)
                                      ->exists();
        }

        // 3. Load the Official Ballot (Only Approved Candidates!)
        $this->positions = $this->election->positions()->with(['candidates' => function($query) {
            $query->where('status', 'approved')->with('user');
        }])->orderBy('order')->get();

        // 4. Initialize the selections array so Livewire doesn't throw undefined errors
        foreach ($this->positions as $position) {
            $this->selectedCandidates[$position->id] = [];
        }

        // 5. Load Colleges for the Guest verification form (if enabled)
        if ($this->election->allow_guest_voting && !auth()->check()) {
            $this->colleges = College::orderBy('name')->get();
        }
    }

    public function castBallot()
    {
        // Security Lock 1: Is Voting Open?
        if (!$this->isVotingOpen) {
            session()->flash('error', 'Voting is currently closed.');
            return;
        }

        // Security Lock 2: Did they already vote?
        if ($this->hasVoted) {
            session()->flash('error', 'You have already cast your ballot in this election.');
            return;
        }

        // Security Lock 3: Guest Verification
        if (!auth()->check()) {
            if (!$this->election->allow_guest_voting) {
                session()->flash('error', 'Guest voting is not enabled. Please log in.');
                return;
            }

            $this->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email|max:255',
                'college_id' => 'required|exists:colleges,id',
                'program' => 'required|string|max:255',
                'year_level' => 'required|string|max:50',
            ]);

            $alreadyVoted = VoterLog::where('election_id', $this->election->id)
                                    ->where('guest_email', $this->guest_email)
                                    ->exists();
            
            if ($alreadyVoted) {
                session()->flash('error', 'A ballot has already been cast using this email address.');
                return;
            }
        }

        // NEW SECURITY LOCK 4: Did they actually select anyone?
        $totalSelections = 0;
        foreach ($this->selectedCandidates as $posId => $cands) {
            $totalSelections += count(array_filter((array) $cands));
            
            // Check for over-voting while we are looping
            $position = $this->positions->firstWhere('id', $posId);
            if ($position && count(array_filter((array) $cands)) > $position->max_winners) {
                session()->flash('error', "You selected too many candidates for {$position->title}.");
                return;
            }
        }

        if ($totalSelections === 0) {
            session()->flash('error', 'Your ballot is empty! Please select at least one candidate.');
            return;
        }

        // 5. Encrypt and save!
        try {
            DB::transaction(function () {
                // A. Check-in record
                VoterLog::create([
                    'election_id' => $this->election->id,
                    'user_id' => auth()->id(), 
                    'guest_name' => auth()->check() ? null : $this->guest_name,
                    'guest_email' => auth()->check() ? null : $this->guest_email,
                    'college_id' => auth()->check() ? null : $this->college_id,
                    'program' => auth()->check() ? null : $this->program,
                    'year_level' => auth()->check() ? null : $this->year_level,
                    'voted_at' => now(),
                ]);

                // B. Cast votes
                // Inside castBallot transaction
                foreach ($this->selectedCandidates as $positionId => $candidateIds) {
                    foreach ((array) $candidateIds as $candidateId) {
                        // Only insert if the ID is not 'abstain' and not empty
                        if (!empty($candidateId) && $candidateId !== 'abstain') { 
                            Vote::create([
                                'election_id' => $this->election->id,
                                'election_position_id' => $positionId,
                                'candidate_id' => $candidateId,
                            ]);
                        }
                    }
                }
            });

            $this->hasVoted = true;
            session()->flash('success', 'Your official ballot has been securely cast.');

        } catch (\Exception $e) {
            session()->flash('error', 'Database Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.open.voting-booth')->layout('layouts.madya-template');
    }
}