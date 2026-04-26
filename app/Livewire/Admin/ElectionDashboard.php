<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Election;
use App\Models\Candidate;

class ElectionDashboard extends Component
{
    public Election $election; // Bound via the URL slug
    
    // Rejection State
    public $candidateToReject = null;
    public $rejectRemarks = '';

    public function mount(Election $election)
    {
        // Security check
        if (auth()->user()->role?->role_name !== 'administrator' && $election->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->election = $election;
    }

    // ... keep your approveCandidate, confirmRejection, and rejectCandidate methods exactly the same ...

    public function render()
    {
        // Fetch candidates ONLY for this specific election
        $candidates = Candidate::with(['user', 'position', 'college', 'platforms', 'credentials'])
            ->where('election_id', $this->election->id)
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->get();

        return view('livewire.admin.election-dashboard', [
            'candidates' => $candidates
        ])->layout('layouts.madya-admin-deck');
    }
}