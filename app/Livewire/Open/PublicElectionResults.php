<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Election;
use App\Models\VoterLog;

class PublicElectionResults extends Component
{
    public Election $election;
    public $isReleased = false;
    public $isCandidacyClosed = false; // NEW STATE

    public function mount(Election $election)
    {
        $this->election = $election;
        $this->checkReleaseStatus();
    }

    public function checkReleaseStatus()
    {
        $now = now();

        // 1. Are the final votes released?
        $this->isReleased = $this->election->results_release && $now >= $this->election->results_release;

        // 2. Is the candidacy filing period over?
        // Checks 'candidacy_end' if it exists. Otherwise, unlocks when 'voting_start' hits.
        $this->isCandidacyClosed = $this->election->candidacy_end
            ? $now >= $this->election->candidacy_end
            : ($this->election->voting_start && $now >= $this->election->voting_start);
    }

    public function render()
    {
        $totalTurnout = VoterLog::where('election_id', $this->election->id)->count();

        $positions = $this->election->positions()->with(['candidates' => function ($query) {
            $query->where('status', 'approved')->with(['user', 'college']);

            if ($this->isReleased) {
                $query->withCount('votes')->orderByDesc('votes_count');
            } else {
                $query->inRandomOrder();
            }
        }])->orderBy('order')->get();

        return view('livewire.open.public-election-results', [
            'positions' => $positions,
            'totalTurnout' => $totalTurnout
        ])->layout('layouts.madya-template');
    }
}
