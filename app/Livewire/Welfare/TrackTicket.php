<?php

namespace App\Livewire\Welfare;

use Livewire\Component;
use App\Models\IncidentReport;
use Livewire\Attributes\Layout;

#[Layout('layouts.welfare')] 
class TrackTicket extends Component
{
    public $case_number = '';
    public $email = '';
    
    public $ticket = null;
    public $hasSearched = false;

    // If they click the link in their email, pre-fill the case number!
    public function mount()
    {
        $this->case_number = request()->query('case', '');
    }

    public function searchTicket()
    {
        $this->validate([
            'case_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $this->hasSearched = true;

        // Securely fetch the ticket ONLY if both the Case Number AND Email match perfectly
        $this->ticket = IncidentReport::where('case_number', strtoupper($this->case_number))
                                      ->where('email', strtolower($this->email))
                                      ->first();

        if (!$this->ticket) {
            session()->flash('error', 'No incident report found with this Case Number and Email combination. Please check your spelling and try again.');
        }
    }

    public function resetSearch()
    {
        $this->ticket = null;
        $this->hasSearched = false;
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.welfare.track-ticket');
    }
}