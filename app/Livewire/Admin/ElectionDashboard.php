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

    // --- VETTING ACTIONS ---

    public function approveCandidate($candidateId)
    {
        $candidate = \App\Models\Candidate::findOrFail($candidateId);
        
        // Update the status
        $candidate->update([
            'status' => 'approved'
        ]);

        // Trigger UI Success
        session()->flash('success', 'Candidate ' . $candidate->user->name . ' has been officially APPROVED for the ballot.');
        
        // PRO TIP: If you set up emails later, this is exactly where you would trigger 
        // Mail::to($candidate->user->email)->send(new CandidateApprovedMail($candidate));
    }

    public function rejectCandidate($candidateId)
    {
        $candidate = \App\Models\Candidate::findOrFail($candidateId);
        
        // Update the status
        $candidate->update([
            'status' => 'rejected'
        ]);

        // Trigger UI Warning/Success
        session()->flash('success', 'Candidate ' . $candidate->user->name . ' has been REJECTED.');
        
        // PRO TIP: Send a rejection email here if needed.
    }

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