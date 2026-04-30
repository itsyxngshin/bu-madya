<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\College;
use App\Models\VoterLog;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class VotingBooth extends Component
{
    public Election $election;
    public $isVotingOpen = false;
    public $hasVoted = false;
    public $colleges = [];

    // Guest fields
    public $guest_name;
    public $guest_email;
    public $college_id;
    public $program;
    public $year_level;

    // Selections
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

        if ($this->election->allow_guest_voting && !auth()->check()) {
            $this->colleges = College::orderBy('name')->get();
        }

        // Initialize the selections array using the basic relationship
        foreach ($this->election->positions as $position) {
            $this->selections[$position->id] = [];
        }
    }

    public function toggleSelection($positionId, $candidateId)
    {
        // Fetch the specific position locally to check max_winners
        $position = ElectionPosition::find($positionId);
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

    public function castBallot()
    {
        if (!$this->isVotingOpen || $this->hasVoted) {
            session()->flash('error', 'Voting is closed or you have already voted.');
            return;
        }

        // 1. Guest Validation
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

        // 2. STRICT BALLOT COMPLETION VALIDATION
        $missingPositions = [];

        foreach ($this->election->positions as $position) {
            $picked = $this->selections[$position->id] ?? [];
            $picked = array_filter((array) $picked); // Remove any null/empty values

            if (empty($picked)) {
                $missingPositions[] = $position->title;
            }
        }

        // If any position is completely empty, block the submission
        if (count($missingPositions) > 0) {
            $positionsList = implode(', ', $missingPositions);
            session()->flash('error', "Incomplete Ballot: Please select a candidate or explicitly choose 'Abstain' for the following positions: {$positionsList}.");
            return;
        }

        // 3. Save the Ballot
        DB::transaction(function () {
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
        $this->election->loadMissing('parties');

        $positions = $this->election->positions()->with(['candidates' => function($query) {
            $query->where('status', 'approved')->with(['user', 'party']);
        }])->orderBy('order')->get();

        return view('livewire.open.voting-booth', [
            'positions' => $positions
        ])->layout('layouts.madya-template');
    }
}
