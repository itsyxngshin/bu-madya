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
        // Security check: Only admins or the creator can view the backend results
        if (auth()->user()->role?->role_name !== 'administrator' && $election->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->election = $election;
    }

    public function render()
    {
        // Get exactly how many people cast a ballot
        $totalTurnout = VoterLog::where('election_id', $this->election->id)->count();

        // Fetch approved candidates and their exact vote counts
        $positions = $this->election->positions()->with(['candidates' => function ($query) {
            $query->where('status', 'approved')
                  ->with('user')
                  ->withCount('votes')
                  ->orderByDesc('votes_count');
        }])->orderBy('order')->get();

        return view('livewire.admin.election-results', [
            'positions' => $positions,
            'totalTurnout' => $totalTurnout
        ])->layout('layouts.madya-admin-deck');
    }
}
