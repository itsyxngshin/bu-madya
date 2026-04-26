<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Election;
use App\Models\VoterLog;
use App\Models\Vote;
use App\Models\College;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VotingBooth extends Component
{
    public Election $election;
    
    // UI State
    public $phase = 'verification'; // verification, ballot, success
    public $errorMessage = '';

    // Guest Data
    public $guest_name = '';
    public $guest_email = '';
    public $college_id = '';
    public $program = '';
    public $year_level = '';
    public $colleges = [];

    // Ballot Data (Format: position_id => [candidate_id_1, candidate_id_2])
    public $selectedCandidates = [];

    public function mount($id)
    {
        $this->election = Election::with(['positions.candidates' => function($query) {
            $query->where('status', 'approved')->with('user');
        }])->findOrFail($id);

        $this->colleges = College::orderBy('name')->get();

        // 1. TIMELINE CHECK
        $now = now();
        if ($now < $this->election->voting_start) {
            $this->errorMessage = 'Voting has not started yet. It opens on ' . $this->election->voting_start->format('M d, Y h:i A');
            return;
        }
        if ($now > $this->election->voting_end) {
            $this->errorMessage = 'Voting has officially closed.';
            return;
        }

        // 2. AUTH & DOUBLE-VOTING CHECK
        if (Auth::check()) {
            $hasVoted = VoterLog::where('election_id', $this->election->id)
                ->where('user_id', Auth::id())
                ->exists();

            if ($hasVoted) {
                $this->errorMessage = 'You have already cast your ballot for this election.';
                return;
            }
            
            // Logged in users bypass the guest form
            $this->phase = 'ballot';
        } else {
            if (!$this->election->allow_guest_voting) {
                $this->errorMessage = 'You must be logged in to vote in this election.';
            }
        }

        // Initialize empty arrays for selections
        foreach ($this->election->positions as $pos) {
            $this->selectedCandidates[$pos->id] = [];
        }
    }

    public function verifyGuest()
    {
        $this->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email',
            'college_id' => 'required|exists:colleges,id',
            'program' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
        ]);

        $hasVoted = VoterLog::where('election_id', $this->election->id)
            ->where('guest_email', $this->guest_email)
            ->exists();

        if ($hasVoted) {
            $this->errorMessage = 'This email address has already been used to cast a ballot.';
            return;
        }

        $this->phase = 'ballot';
    }

    public function castVote()
    {
        // 1. Validate Selections (Ensure they don't select more than max_winners)
        foreach ($this->election->positions as $pos) {
            $selectedForPos = count($this->selectedCandidates[$pos->id] ?? []);
            if ($selectedForPos > $pos->max_winners) {
                session()->flash('error', "You selected too many candidates for {$pos->title}. Max allowed: {$pos->max_winners}.");
                return;
            }
        }

        // 2. THE SECURE TRANSACTION
        DB::transaction(function () {
            
            // A. Sign the Check-In Sheet (VoterLog)
            VoterLog::create([
                'election_id' => $this->election->id,
                'user_id' => Auth::check() ? Auth::id() : null,
                'guest_email' => Auth::check() ? null : $this->guest_email,
                'guest_name' => Auth::check() ? null : $this->guest_name,
                'college_id' => Auth::check() ? null : $this->college_id,
                'program' => Auth::check() ? null : $this->program,
                'year_level' => Auth::check() ? null : $this->year_level,
                'voted_at' => now(),
            ]);

            // B. Drop the Anonymous Ballots
            foreach ($this->selectedCandidates as $positionId => $candidateIds) {
                foreach ($candidateIds as $candidateId) {
                    // Safety check so we don't save empty checkboxes
                    if (!empty($candidateId)) {
                        Vote::create([
                            'election_id' => $this->election->id,
                            'election_position_id' => $positionId,
                            'candidate_id' => $candidateId,
                        ]);
                    }
                }
            }
        });

        $this->phase = 'success';
    }

    public function render()
    {
        return view('livewire.open.voting-booth')->layout('layouts.madya-template');
    }
}