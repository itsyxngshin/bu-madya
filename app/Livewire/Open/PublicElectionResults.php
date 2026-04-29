<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Election;
use App\Models\VoterLog;

class PublicElectionResults extends Component
{
    public Election $election;
    public $isReleased = false;
    public $isCandidacyClosed = false;
    public $totalTurnout = 0;

    public function mount(Election $election)
    {
        $this->election = $election;
        $this->updateStatuses();
    }

    // Called by Alpine.js when the countdown hits zero
    public function checkReleaseStatus()
    {
        $this->updateStatuses();
    }

    public function updateStatuses()
    {
        $now = now();
        
        // 1. Check if Filing of Candidacy is Over
        $this->isCandidacyClosed = $this->election->application_end && $now->greaterThanOrEqualTo($this->election->application_end);
        
        // 2. Check if Results are Publicly Released
        $this->isReleased = $this->election->results_release && $now->greaterThanOrEqualTo($this->election->results_release);
        
        // 3. Update Live Voter Turnout
        $this->totalTurnout = VoterLog::where('election_id', $this->election->id)->count();
    }

    public function render()
    {
        // Re-run the time check on every wire:poll so the UI updates automatically
        $this->updateStatuses();

        // Fetch positions and approved candidates. 
        // We load the vote count here so the frontend can calculate the percentages.
        $positions = $this->election->positions()->with(['candidates' => function($query) {
            $query->where('status', 'approved')
                  ->with(['user', 'college'])
                  ->withCount('votes') // Auto-magically creates 'votes_count'
                  ->orderByDesc('votes_count'); // Sort winners to the top
        }])->orderBy('order')->get();

        return view('livewire.open.public-election-results', [
            'positions' => $positions
        ])->layout('layouts.madya-template');
    }
}