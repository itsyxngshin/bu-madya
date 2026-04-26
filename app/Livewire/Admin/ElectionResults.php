<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Election;
use App\Models\VoterLog;

class ElectionResults extends Component
{
    public Election $election;

    public function mount(Election $election)
    {
        if (auth()->user()->role?->role_name !== 'administrator' && $election->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->election = $election;
    }

    public function render()
    {
        // 1. Get the total number of people who checked in
        $totalVoters = VoterLog::where('election_id', $this->election->id)->count();

        // 2. Fetch the election positions and approved candidates WITH their vote counts
        $electionData = Election::with(['positions' => function ($query) {
            $query->orderBy('order');
        }, 'positions.candidates' => function ($query) {
            $query->where('status', 'approved')
                  ->with('user')
                  ->withCount('votes')
                  ->orderByDesc('votes_count');
        }])->find($this->election->id);

        return view('livewire.admin.election-results', [
            'electionData' => $electionData, // Pass the fresh data to the view
            'totalVoters' => $totalVoters
        ])->layout('layouts.madya-admin-deck');
    }
}