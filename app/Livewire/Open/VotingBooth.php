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
    public Election $election; 
    public $isVotingOpen = false;
    public $hasVoted = false;
    public $positions;
    public $colleges = [];
    
    // Guest fields
    public $guest_name;
    public $guest_email;
    public $college_id;
    public $program;
    public $year_level;

    // THE FIX: Array to hold live selections
    public $selections = []; 

    public function mount(Election $election)
    {
        $this->election = $election;
        $now = now();

        $this->isVotingOpen = (
            $this->election->status === 'active' &&
            $this->election->voting_start &&
            $this->election->voting_end &&
            $now->between($this->election->voting_start, $this->election->voting_end)
        );

        if (auth()->check()) {
            $this->hasVoted = VoterLog::where('user_id', auth()->id())
                                      ->where('election_id', $this->election->id)
                                      ->exists();
        }

        // Fetch APPROVED candidates
        $this->positions = $this->election->positions()->with(['candidates' => function($query) {
            $query->where('status', 'approved')->with('user');
        }])->orderBy('order')->get();

        if ($this->election->allow_guest_voting && !auth()->check()) {
            $this->colleges = College::orderBy('name')->get();
        }

        // Initialize the selections array for each position to prevent undefined array key errors
        foreach ($this->positions as $position) {
            $this->selections[$position->id] = [];
        }
    }

    // THE FIX: The missing method your Blade file was trying to call!
    public function toggleSelection($positionId, $candidateId)
    {
        $position = $this->positions->firstWhere('id', $positionId);
        if (!$position) return;

        $currentSelections = $this->selections[$positionId] ?? [];

        // Logic 1: If they click "Abstain"
        if ($candidateId === 'abstain') {
            if (in_array('abstain', $currentSelections)) {
                $this->selections[$positionId] = []; // Un-abstain
            } else {
                $this->selections[$positionId] = ['abstain']; // Clear candidates, set to abstain
            }
            return;
        }

        // Logic 2: If they click a candidate while currently abstaining, clear abstain first
        if (in_array('abstain', $currentSelections)) {
            $currentSelections = [];
        }

        // Logic 3: Toggle the candidate
        if (in_array($candidateId, $currentSelections)) {
            // Deselect candidate
            $this->selections[$positionId] = array_values(array_diff($currentSelections, [$candidateId]));
        } else {
            // Select candidate (but check limits first)
            if (count($currentSelections) < $position->max_winners) {
                $currentSelections[] = $candidateId;
                $this->selections[$positionId] = array_values($currentSelections);
            } else {
                session()->flash('error', "You can only select up to {$position->max_winners} candidate(s) for {$position->title}.");
            }
        }
    }

    // THE FIX: Removed the Alpine $payload dependency, now uses $this->selections natively
    public function castBallot()
    {
        if (!$this->isVotingOpen || $this->hasVoted) {
            session()->flash('error', 'Voting is closed or you have already voted.');
            return;
        }

        // Guest Validation
        if (!auth()->check()) {
            if (!$this->election->allow_guest_voting) {
                session()->flash('error', 'Guest voting is disabled for this election.');
                return;
            }
            
            $this->validate([
                'guest_name' => 'required|string|max:255', 
                'guest_email' => 'required|email|max:255',
                'college_id' => 'required|exists:colleges,id', 
                'program' => 'required|string|max:255',
                'year_level' => 'required|string|max:50',
            ]);
            
            if (VoterLog::where('election_id', $this->election->id)->where('guest_email', $this->guest_email)->exists()) {
                session()->flash('error', 'A ballot has already been cast using this email address.');
                return;
            }
        }

        // Ensure the ballot isn't completely empty
        $totalVotes = 0;
        foreach ($this->selections as $posId => $picked) {
            $totalVotes += count(array_filter((array) $picked));
        }

        if ($totalVotes === 0) { 
            session()->flash('error', 'Your ballot is completely empty. Please make selections or explicitly abstain from positions before casting.'); 
            return; 
        }

        // Save safely inside a database transaction
        DB::transaction(function () {
            // 1. Create the Voter Log
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

            // 2. Deposit the encrypted/anonymous votes
            foreach ($this->selections as $posId => $candidateIds) {
                foreach ((array) $candidateIds as $candId) {
                    if (!empty($candId) && $candId !== 'abstain') { 
                        Vote::create([
                            'election_id' => $this->election->id, 
                            'election_position_id' => $posId, 
                            'candidate_id' => $candId
                        ]);
                    }
                }
            }
        });

        $this->hasVoted = true;
    }

    public function render() 
    { 
        return view('livewire.open.voting-booth')->layout('layouts.madya-template'); 
    }
}