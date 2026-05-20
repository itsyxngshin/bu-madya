<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;
use App\Models\AccreditationApplication;
use App\Models\AcademicYear;
use App\Models\College;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]

class AccreditationWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $application_id = null; // Tracks if we are editing an existing draft/returned application

    // STEP 1: General Info & Finance
    public $name, $type, $type_specification, $year_established, $email_address, $facebook_account;
    public $membership_fee = 0;
    public $collection_frequency = 'none';
    public $bank_account_name, $bank_account_number, $bank_name;
    public $academic_year_id;

    // STEP 2: Documents (New Uploads)
    public $application_type = 'accreditation';
    public $bankbook_photo, $cbl, $recent_fliers;
    public $accomplishment_report, $audited_financial_report;

    // STEP 2: Existing Document Paths (Loaded from Draft)
    public $existing_bankbook, $existing_cbl, $existing_fliers;
    public $existing_accomplishment, $existing_audited;

    // STEPS 3-5: Dynamic Arrays
    public $officers = [];
    public $members = [];
    public $activities = [];

    // STEP 6: Signatories (Text & New Uploads)
    public $president_name, $president_contact, $president_email, $president_signature;
    public $adviser_name, $adviser_contact, $adviser_email, $adviser_signature;

    // STEP 6: Existing Signatures (Loaded from Draft)
    public $existing_president_signature, $existing_adviser_signature;
    public $committee_type = 'CBO';

    public function mount()
    {
        $org = Organization::where('user_id', Auth::id())->first();

        if ($org) {
            // Load Base Org Details
            $this->name = $org->name;
            $this->type = $org->type;
            $this->type_specification = $org->type_specification;
            $this->year_established = $org->year_established;
            $this->email_address = $org->email_address;
            $this->facebook_account = $org->facebook_account;
            $this->membership_fee = $org->membership_fee;
            $this->collection_frequency = $org->collection_frequency;

            // Check for an existing Draft or Returned Application
            $draft = $org->applications()->whereIn('status', ['draft', 'returned'])->latest()->first();

            if ($draft) {
                $this->application_id = $draft->id;
                $this->academic_year_id = $draft->academic_year_id;
                $this->application_type = $draft->application_type;

                $this->bank_account_name = $draft->bank_account_name;
                $this->bank_account_number = $draft->bank_account_number;
                $this->bank_name = $draft->bank_name;

                // Load existing file paths so user doesn't have to re-upload
                $this->existing_bankbook = $draft->bankbook_photo_path;
                $this->existing_cbl = $draft->cbl_path;
                $this->existing_fliers = $draft->recent_fliers_path;
                $this->existing_accomplishment = $draft->accomplishment_report_path;
                $this->existing_audited = $draft->audited_financial_report_path;
                $this->existing_president_signature = $draft->president_signature_path;
                $this->existing_adviser_signature = $draft->adviser_signature_path;

                // Load Signatory text details
                $this->president_name = $draft->president_name;
                $this->president_contact = $draft->president_contact;
                $this->president_email = $draft->president_email;
                $this->adviser_name = $draft->adviser_name;
                $this->adviser_contact = $draft->adviser_contact;
                $this->adviser_email = $draft->adviser_email;
                $this->committee_type = $draft->committee_type ?? 'CBO';

                // Load existing dynamic arrays
                $this->officers = $draft->officers->toArray();
                $this->members = $draft->members->toArray();
                $this->activities = $draft->activities->toArray();
            }
        }

        // Initialize one empty row if arrays are empty
        if (empty($this->officers)) $this->addOfficer();
        if (empty($this->members)) $this->addMember();
        if (empty($this->activities)) $this->addActivity();
    }

    // --- NAVIGATION & VALIDATION ---

    public function nextStep()
    {
        $this->validateStep();
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    private function validateStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'type_specification' => 'required_if:type,others',
                'academic_year_id' => 'required|exists:academic_years,id',
                'year_established' => 'required|numeric|digits:4',
                'email_address' => 'required|email',
                'facebook_account' => 'required|string',
                'membership_fee' => 'required|numeric|min:0',
                'collection_frequency' => 'required|in:annual,semestral,none',
                'bank_name' => 'nullable|string',
                'bank_account_number' => 'nullable|string',
            ]);
        } elseif ($this->currentStep === 2) {
            // Require files ONLY if they haven't uploaded them previously in a draft
            $rules = [
                'application_type' => 'required|in:accreditation,reaccreditation',
                'bankbook_photo' => $this->existing_bankbook ? 'nullable|image|max:5120' : 'required|image|max:5120',
                'cbl' => $this->existing_cbl ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240',
                'recent_fliers' => $this->existing_fliers ? 'nullable|file|mimes:pdf,jpg,png|max:5120' : 'required|file|mimes:pdf,jpg,png|max:5120',
            ];

            if ($this->application_type === 'reaccreditation') {
                $rules['accomplishment_report'] = $this->existing_accomplishment ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240';
                $rules['audited_financial_report'] = $this->existing_audited ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240';
            }
            $this->validate($rules);
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'officers.*.position' => 'required|string',
                'officers.*.complete_name' => 'required|string',
                'officers.*.course_and_year' => 'required|string',
                'officers.*.college_id' => 'required|exists:colleges,id',
                'officers.*.contact_number' => 'required|string',
                'officers.*.email_address' => 'required|email',
            ]);
        } elseif ($this->currentStep === 4) {
            $this->validate([
                'members.*.complete_name' => 'required|string',
                'members.*.course_and_year' => 'required|string',
                'members.*.college_id' => 'required|exists:colleges,id',
            ]);
        } elseif ($this->currentStep === 5) {
            $this->validate([
                'activities.*.title' => 'required|string',
                'activities.*.description' => 'required|string',
            ]);
        }
    }

    // --- DYNAMIC ARRAY MANAGERS ---
    public function addOfficer() { $this->officers[] = ['position' => '', 'complete_name' => '', 'course_and_year' => '', 'college_id' => '', 'contact_number' => '', 'email_address' => '']; }
    public function removeOfficer($index) { unset($this->officers[$index]); $this->officers = array_values($this->officers); }
    public function addMember() { $this->members[] = ['complete_name' => '', 'course_and_year' => '', 'college_id' => '', 'contact_number' => '']; }
    public function removeMember($index) { unset($this->members[$index]); $this->members = array_values($this->members); }
    public function addActivity() { $this->activities[] = ['title' => '', 'description' => '', 'target_month' => '']; }
    public function removeActivity($index) { unset($this->activities[$index]); $this->activities = array_values($this->activities); }

    // --- SAVING LOGIC ---

    public function saveDraft()
    {
        // Minimal validation to save a draft
        $this->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
        ], ['academic_year_id.required' => 'An Academic Year is required to save a draft.']);

        $this->processSave('draft');
        session()->flash('success', 'Draft saved successfully! You can resume this later.');
    }

    public function submit()
    {
        // Final strict validation for Step 6
        $this->validate([
            'president_name' => 'required|string',
            'president_email' => 'required|email',
            'adviser_name' => 'required|string',
            'adviser_email' => 'required|email',
            'president_signature' => $this->existing_president_signature ? 'nullable|image' : 'required|image',
            'adviser_signature' => $this->existing_adviser_signature ? 'nullable|image' : 'required|image',
        ]);

        $this->processSave('pending');
        session()->flash('success', 'Application successfully submitted to OSAS for review.');
        return redirect()->route('accreditation.dashboard');
    }

    private function processSave($status)
    {
        DB::transaction(function () use ($status) {

            // 1. Create/Update Org Profile
            $org = Organization::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'name' => $this->name,
                    'type' => $this->type,
                    'type_specification' => $this->type_specification,
                    'year_established' => $this->year_established,
                    'email_address' => $this->email_address,
                    'facebook_account' => $this->facebook_account,
                    'membership_fee' => $this->membership_fee,
                    'collection_frequency' => $this->collection_frequency,
                ]
            );

            // 2. Handle File Uploads (Prioritize newly uploaded file, fallback to existing)
            $paths = [
                'bankbook_photo_path' => $this->bankbook_photo ? $this->bankbook_photo->store('accreditation/bankbooks', 'public') : $this->existing_bankbook,
                'cbl_path' => $this->cbl ? $this->cbl->store('accreditation/cbl', 'public') : $this->existing_cbl,
                'recent_fliers_path' => $this->recent_fliers ? $this->recent_fliers->store('accreditation/fliers', 'public') : $this->existing_fliers,
                'accomplishment_report_path' => $this->accomplishment_report ? $this->accomplishment_report->store('accreditation/accomplishments', 'public') : $this->existing_accomplishment,
                'audited_financial_report_path' => $this->audited_financial_report ? $this->audited_financial_report->store('accreditation/audits', 'public') : $this->existing_audited,
                'president_signature_path' => $this->president_signature ? $this->president_signature->store('accreditation/signatures', 'public') : $this->existing_president_signature,
                'adviser_signature_path' => $this->adviser_signature ? $this->adviser_signature->store('accreditation/signatures', 'public') : $this->existing_adviser_signature,
            ];

            // 3. Create or Update Application Record
            $application = AccreditationApplication::updateOrCreate(
                ['id' => $this->application_id],
                array_merge([
                    'organization_id' => $org->id,
                    'academic_year_id' => $this->academic_year_id,
                    'application_type' => $this->application_type,
                    'status' => $status,
                    'bank_account_name' => $this->bank_account_name,
                    'bank_account_number' => $this->bank_account_number,
                    'bank_name' => $this->bank_name,
                    'president_name' => $this->president_name,
                    'president_contact' => $this->president_contact,
                    'president_email' => $this->president_email,
                    'adviser_name' => $this->adviser_name,
                    'adviser_contact' => $this->adviser_contact,
                    'adviser_email' => $this->adviser_email,
                    'committee_type' => $this->committee_type,
                ], $paths)
            );

            // Update local ID so subsequent "Save Drafts" update instead of duplicate
            $this->application_id = $application->id;

            // 4. Wipe old arrays and save new ones (filtering out empty fields)
            $application->officers()->delete();
            $application->members()->delete();
            $application->activities()->delete();

            $application->officers()->createMany(array_filter($this->officers, fn($o) => !empty($o['complete_name'])));
            $application->members()->createMany(array_filter($this->members, fn($m) => !empty($m['complete_name'])));
            $application->activities()->createMany(array_filter($this->activities, fn($a) => !empty($a['title'])));
        });
    }

    public function render()
    {
        return view('livewire.accreditation-wizard', [
            'academicYears' => AcademicYear::orderBy('id', 'desc')->get(),
            'colleges' => College::orderBy('name')->get(),
        ]);
    }
}
