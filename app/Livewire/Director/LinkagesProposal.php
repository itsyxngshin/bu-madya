<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout; 
use App\Models\LinkageProposal; 
use App\Mail\ProposalReceivedMail;     
use Illuminate\Support\Facades\Mail;    

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
        'phone' => 'nullable|string|max:20', // <-- 2. Added missing phone validation!
        'title' => 'required|min:5',
        'message' => 'required|min:20',
        'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB Max
    ];

    public function submit()
    {
        $this->validate();

        $filePath = null;
        if ($this->file) {
            $filePath = $this->file->store('proposals', 'public');
        }

        // 1. Save to Database and assign it to a variable ($proposal)
        $proposal = LinkageProposal::create([
            'organization_name' => $this->orgName,
            'contact_person'    => $this->contactPerson,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'partnership_type'  => $this->type,
            'title'             => $this->title,
            'message'           => $this->message,
            'file_path'         => $filePath, 
        ]);

        // 2. Send the automated receipt email to the partner
        try {
            Mail::to($this->email)->send(new ProposalReceivedMail($proposal));
        } catch (\Exception $e) {
            // Log the error so the app doesn't crash if the mail server fails
            \Log::error('Failed to send proposal receipt: ' . $e->getMessage());
        }
        
        $this->reset();
        session()->flash('success', 'Proposal submitted successfully! We will review it and get back to you within 3-5 business days.');
    }

    public function render()
    {
        return view('livewire.director.linkages-proposal');
    }
}