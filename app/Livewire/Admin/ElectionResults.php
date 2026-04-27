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
        $totalTurnout = VoterLog::where('election_id', $this->election->id)->count();

        // THE FIX: Explicitly eager load 'college' so it doesn't crash or run slow
        $positions = $this->election->positions()->with(['candidates' => function ($query) {
            $query->where('status', 'approved')
                  ->with(['user', 'college'])
                  ->withCount('votes')
                  ->orderByDesc('votes_count');
        }])->orderBy('order')->get();

        return view('livewire.admin.election-results', [
            'positions' => $positions,
            'totalTurnout' => $totalTurnout
        ])->layout('layouts.madya-admin-deck');
    }
}
