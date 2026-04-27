<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Candidate;

class CandidateProfile extends Component
{
    public Candidate $candidate;

    public function mount(Candidate $candidate)
    {
        if ($candidate->status !== 'approved') {
            abort(404, 'This candidate profile is not available.');
        }

        $this->candidate->load([
            'user', 
            'college', 
            'electionPosition.election', 
            'platforms', 
            'credentials'
        ]);
    }

    public function render()
    {
        return view('livewire.open.candidate-profile')->layout('layouts.madya-template');
    }
}