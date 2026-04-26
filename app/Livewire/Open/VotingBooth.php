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

    // Ballot Data
    public $positions;
    public $selectedCandidates = []; 

    // Guest Voter Form
    public $colleges = [];
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

        foreach ($this->positions as $position) {
            $this->selectedCandidates[$position->id] = [];
        }

        if ($this->election->allow_guest_voting && !auth()->check()) {
            $this->colleges = College::orderBy('name')->get();
        }
    }

    // THE CRITICAL MISSING METHOD FOR CLICKING CANDIDATES
    public function toggleSelection($positionId, $candidateId)
    {
        $current = $this->selectedCandidates[$positionId] ?? [];
        $position = $this->positions->firstWhere('id', $positionId);
        $candidateId = (string) $candidateId;

        // 1. Handle "Abstain" explicitly
        if ($candidateId === 'abstain') {
            if (in_array('abstain', $current)) {
                $this->selectedCandidates[$positionId] = [];
            } else {
                $this->selectedCandidates[$positionId] = ['abstain'];
            }
            return;
        }

        // 2. Clear abstain if a real candidate is chosen
        if (($key = array_search('abstain', $current)) !== false) {
            unset($current[$key]);
            $current = array_values($current); // re-index
        }

        // 3. Toggle Candidate Selection
        if (($key = array_search($candidateId, $current)) !== false) {
            unset($current[$key]);
            $current = array_values($current); // re-index
        } else {
            if (count($current) < $position->max_winners) {
                $current[] = $candidateId;
            }
        }

        $this->selectedCandidates[$positionId] = $current;
    }

    public function castBallot()
    {
        if (!$this->isVotingOpen) {
            session()->flash('error', 'Voting is currently closed.');
            return;
        }

        if ($this->hasVoted) {
            session()->flash('error', 'You have already cast your ballot in this election.');
            return;
        }

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

        // Validate Selections (Ensure not empty, and no over-voting)
        $totalSelections = 0;
        foreach ($this->selectedCandidates as $posId => $cands) {
            $totalSelections += count(array_filter((array) $cands));
            
            $position = $this->positions->firstWhere('id', $posId);
            if ($position && count(array_filter((array) $cands)) > $position->max_winners) {
                session()->flash('error', "You selected too many candidates for {$position->title}.");
                return;
            }
        }

        if ($totalSelections === 0) {
            session()->flash('error', 'Your ballot is empty! Please select at least one option.');
            return;
        }

        try {
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

                foreach ($this->selectedCandidates as $positionId => $candidateIds) {
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