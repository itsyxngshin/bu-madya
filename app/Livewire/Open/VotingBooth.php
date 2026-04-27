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

        // CRITICAL SECURITY: Only fetch APPROVED candidates.
        $this->positions = $this->election->positions()->with(['candidates' => function($query) {
            $query->where('status', 'approved')->with('user');
        }])->orderBy('order')->get();

        if ($this->election->allow_guest_voting && !auth()->check()) {
            $this->colleges = College::orderBy('name')->get();
        }
    }

    public function castBallot($payload = '{}')
    {
        if (!$this->isVotingOpen || $this->hasVoted) {
            session()->flash('error', 'Voting is closed or you have already voted.');
            return;
        }

        if (!auth()->check()) {
            if (!$this->election->allow_guest_voting) {
                session()->flash('error', 'Guest voting disabled.');
                return;
            }
            $this->validate([
                'guest_name' => 'required|string|max:255', 'guest_email' => 'required|email|max:255',
                'college_id' => 'required|exists:colleges,id', 'program' => 'required|string|max:255',
                'year_level' => 'required|string|max:50',
            ]);
            if (VoterLog::where('election_id', $this->election->id)->where('guest_email', $this->guest_email)->exists()) {
                session()->flash('error', 'A ballot has already been cast using this email.');
                return;
            }
        }

        $selections = json_decode($payload, true);
        if (!is_array($selections) || empty($selections)) {
            session()->flash('error', 'Ballot empty.'); return;
        }

        $totalVotes = 0;
        foreach ($this->positions as $position) {
            $picked = $selections[$position->id] ?? [];
            $totalVotes += count(array_filter((array) $picked));
            if (count((array) $picked) > $position->max_winners && !in_array('abstain', (array) $picked)) {
                session()->flash('error', "Too many selected for {$position->title}."); return;
            }
        }
        if ($totalVotes === 0) { session()->flash('error', 'Your ballot is empty.'); return; }

        DB::transaction(function () use ($selections) {
            VoterLog::create([
                'election_id' => $this->election->id, 'user_id' => auth()->id(),
                'guest_name' => auth()->check() ? null : $this->guest_name,
                'guest_email' => auth()->check() ? null : $this->guest_email,
                'college_id' => auth()->check() ? null : $this->college_id,
                'program' => auth()->check() ? null : $this->program,
                'year_level' => auth()->check() ? null : $this->year_level,
                'voted_at' => now(),
            ]);

            foreach ($selections as $posId => $candidateIds) {
                foreach ((array) $candidateIds as $candId) {
                    if (!empty($candId) && $candId !== 'abstain') {
                        Vote::create(['election_id' => $this->election->id, 'election_position_id' => $posId, 'candidate_id' => $candId]);
                    }
                }
            }
        });
        $this->hasVoted = true;
    }

    public function render() { return view('livewire.open.voting-booth')->layout('layouts.madya-template'); }
}
