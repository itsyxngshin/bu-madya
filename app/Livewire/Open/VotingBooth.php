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
    
    // Livewire natively holds the state
    public array $selections = []; 
    
    public $guest_name;
    public $guest_email;
    public $college_id;
    public $program;
    public $year_level;

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

        $this->positions = $this->election->positions()->with(['candidates' => function($query) {
            $query->where('status', 'approved')->with('user');
        }])->orderBy('order')->get();

        // Initialize empty arrays for each position
        foreach ($this->positions as $position) {
            $this->selections[(string)$position->id] = [];
        }

        if ($this->election->allow_guest_voting && !auth()->check()) {
            $this->colleges = College::orderBy('name')->get();
        }
    }

    // THE FIX: Move the selection logic to PHP
    public function toggleSelection($positionId, $candidateId)
    {
        if (!$this->isVotingOpen) return;

        $positionId = (string) $positionId;
        $candidateId = (string) $candidateId;

        // Fallback initialization
        if (!isset($this->selections[$positionId])) {
            $this->selections[$positionId] = [];
        }

        $current = $this->selections[$positionId];
        $position = $this->positions->firstWhere('id', $positionId);
        $limit = $position ? $position->max_winners : 1;

        // Handle Abstain
        if ($candidateId === 'abstain') {
            $this->selections[$positionId] = in_array('abstain', $current) ? [] : ['abstain'];
            return;
        }

        // Remove 'abstain' if they pick a real candidate
        $current = array_filter($current, fn($val) => $val !== 'abstain');

        if (in_array($candidateId, $current)) {
            // Deselect candidate
            $current = array_diff($current, [$candidateId]);
        } else {
            // Select candidate (if under limit)
            if (count($current) < $limit) {
                $current[] = $candidateId;
            }
        }

        // array_values forces a clean, zero-indexed array to prevent JSON object conversion
        $this->selections[$positionId] = array_values($current);
    }

    public function castBallot()
    {
        if (!$this->isVotingOpen) {
            session()->flash('error', 'Voting is currently closed.');
            return;
        }

        if ($this->hasVoted) {
            session()->flash('error', 'You have already cast your ballot.');
            return;
        }

        if (!auth()->check()) {
            if (!$this->election->allow_guest_voting) {
                session()->flash('error', 'Guest voting disabled. Please log in.');
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
                session()->flash('error', 'A ballot has already been cast using this email.');
                return;
            }
        }

        $selections = $this->selections;

        if (empty($selections)) {
            session()->flash('error', 'Your ballot is empty!');
            return;
        }

        $totalVotes = 0;

        foreach ($this->positions as $position) {
            $picked = $selections[$position->id] ?? [];
            $totalVotes += count(array_filter((array) $picked));
            
            // Limit check just in case
            if (count((array) $picked) > $position->max_winners && !in_array('abstain', (array) $picked)) {
                session()->flash('error', "Too many candidates selected for {$position->title}.");
                return;
            }
        }

        if ($totalVotes === 0) {
            session()->flash('error', 'Your ballot is empty! Please make your selections.');
            return;
        }

        try {
            DB::transaction(function () use ($selections) {
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

                foreach ($selections as $positionId => $candidateIds) {
                    foreach ((array) $candidateIds as $candidateId) {
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