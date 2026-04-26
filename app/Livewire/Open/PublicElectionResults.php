<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Election;
use App\Models\VoterLog;

class PublicElectionResults extends Component
{
    public Election $election;
    public $isReleased = false;

    public function mount(Election $election)
    {
        // Route Model Binding automatically fetches the election via the slug!
        $this->election = $election;
        $this->checkReleaseStatus();
    }

    public function checkReleaseStatus()
    {
        // Check if current time has passed the release date
        if ($this->election->results_release && now() >= $this->election->results_release) {
            $this->isReleased = true;
        } else {
            $this->isReleased = false;
        }
    }

    public function render()
    {
        // Re-calculate turnout dynamically in case new people are voting
        $totalTurnout = VoterLog::where('election_id', $this->election->id)->count();

        // Only fetch the sensitive vote counts IF the results are officially released
        $positions = $this->election->positions()->with(['candidates' => function ($query) {
            $query->where('status', 'approved')->with('user');
            
            if ($this->isReleased) {
                // If released, bring the votes and sort by highest votes!
                $query->withCount('votes')->orderByDesc('votes_count');
            } else {
                // If not released, randomize the roster so no hints are given
                $query->inRandomOrder(); 
            }
        }])->orderBy('order')->get();

        return view('livewire.open.public-election-results', [
            'positions' => $positions,
            'totalTurnout' => $totalTurnout
        ])->layout('layouts.madya-template'); // Or layouts.madya-template
    }
}