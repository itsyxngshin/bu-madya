<?php

namespace App\Livewire\Welfare;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IncidentReport;
use Illuminate\Support\Facades\Mail;
use App\Mail\IncidentReportSubmitted;
use Livewire\Attributes\Layout;

#[Layout('layouts.welfare')] // We will create a clean layout for the subdomain later!
class SubmitTicket extends Component
{
    use WithFileUploads;

    // Form Fields
    public $first_name = '';
    public $middle_name = '';
    public $last_name = '';
    public $email = '';
    public $phone_number = '';
    public $year_and_block = '';
    public $nature_of_incident = '';
    public $incident_details = '';
    public $file_upload;
    public $assigned_org_id = '';

    public $isSubmitted = false;
    public $generatedCaseNumber = '';

    // Validation Rules (Matching the red asterisks in your mockup)
    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone_number' => 'required|string|max:20',
        'year_and_block' => 'required|string|max:50',
        'assigned_org_id' => 'nullable|exists:users,id',
        'nature_of_incident' => 'required|string',
        'incident_details' => 'required|string',
        'file_upload' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // Max 10MB
    ];

    public function submitReport()
    {
        $this->validate();

        $filePath = null;
        if ($this->file_upload) {
            $filePath = $this->file_upload->store('incident_evidence', 'public');
        }

        $report = IncidentReport::create([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'year_and_block' => $this->year_and_block,
            'nature_of_incident' => $this->nature_of_incident,
            'incident_details' => $this->incident_details,
            'file_upload_path' => $filePath,
            'assigned_org_id' => $this->assigned_org_id ?: null,
        ]);

        // [NEW] Trigger the Automated Email!
        Mail::to($report->email)->send(new IncidentReportSubmitted($report));

        $this->generatedCaseNumber = $report->case_number;
        $this->isSubmitted = true;
    }

    public function render()
    {
        // Fetch ONLY users who are 'organization' AND have welfare access = 1
        $authorizedOrgs = \App\Models\User::whereHas('role', function($query) {
            $query->where('role_name', 'organization');
        })->where('can_manage_welfare', 1)->get();

        return view('livewire.welfare.submit-ticket', [
            'authorizedOrgs' => $authorizedOrgs
        ]);
    }
}
