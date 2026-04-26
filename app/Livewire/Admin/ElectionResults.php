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

        // 2. LOAD the relationships and vote counts directly into the public $election variable!
        $this->election->load([
            'positions' => function ($query) {
                $query->orderBy('order');
            }, 
            'positions.candidates' => function ($query) {
                $query->where('status', 'approved')
                      ->with('user')
                      ->withCount('votes') // This dynamically creates the 'votes_count' attribute
                      ->orderByDesc('votes_count');
            }
        ]);

        return view('livewire.admin.election-results', [
            'totalVoters' => $totalVoters
        ])->layout('layouts.madya-admin-deck');
    }
}