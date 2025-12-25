<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class LinkagesProposal extends Component
{
    use WithFileUploads;

    // Organization Info
    public $orgName;
    public $contactPerson;
    public $email;
    public $phone;

    // Proposal Details
    public $type = 'Event Partnership'; // Default
    public $title;
    public $message;
    public $file; // For PDF/Doc uploads

    protected $rules = [
        'orgName' => 'required|min:2',
        'contactPerson' => 'required|min:2',
        'email' => 'required|email',
        'title' => 'required|min:5',
        'message' => 'required|min:20',
        'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB Max
    ];

    public function submit()
    {
        $this->validate();

        // SIMULATED EMAIL/DB LOGIC
        // Mail::to('bu.madya2025@gmail.com')->send(new ProposalMail($this->all()));
        
        // Reset form
        $this->reset();
        
        // Show success message
        session()->flash('success', 'Proposal submitted successfully! We will review it and get back to you within 3-5 business days.');
    }

    public function render()
    {
        return view('livewire.director.linkages-proposal');
    }
}
