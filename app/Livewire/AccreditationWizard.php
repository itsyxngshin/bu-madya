<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;
use App\Models\AccreditationApplication;
use App\Models\AcademicYear; // Assuming you have this
use App\Models\College;      // Assuming you have this

class AccreditationWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;

    // STEP 1: General Info & Finance
    public $name, $type, $type_specification, $year_established, $email_address, $facebook_account;
    public $membership_fee = 0;
    public $collection_frequency = 'none';
    public $bank_account_name, $bank_account_number, $bank_name;
    public $academic_year_id;

    // STEP 6: Signatories
    public $president_name, $president_contact, $president_email, $president_signature;
    public $adviser_name, $adviser_contact, $adviser_email, $adviser_signature;
    public $committee_type = 'UBO'; // Default to University-Based

    // STEP 2: Documents
    public $application_type = 'accreditation'; // Toggle: accreditation or reaccreditation
    public $bankbook_photo, $cbl, $recent_fliers;
    public $accomplishment_report, $audited_financial_report; // For reaccreditation

    // STEP 3: Officers (Dynamic Array)
    public $officers = [];

    // STEP 4: Members (Dynamic Array)
    public $members = [];

    // STEP 5: Activities (Dynamic Array)
    public $activities = [];

    public function mount()
    {
        // Pre-fill organization data if the user already registered one
        $org = Organization::where('user_id', Auth::id())->first();
        if ($org) {
            $this->name = $org->name;
            $this->type = $org->type;
            $this->type_specification = $org->type_specification;
            $this->year_established = $org->year_established;
            $this->email_address = $org->email_address;
            $this->facebook_account = $org->facebook_account;
            $this->membership_fee = $org->membership_fee;
            $this->collection_frequency = $org->collection_frequency;
        }

        // Initialize dynamic arrays with one empty row
        $this->addOfficer();
        $this->addMember();
        $this->addActivity();
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
                'year_established' => 'required|numeric|digits:4',
                'email_address' => 'required|email',
                'academic_year_id' => 'required|exists:academic_years,id',
                'membership_fee' => 'required|numeric|min:0',
                'collection_frequency' => 'required|in:annual,semestral,none',
                'bank_account_name' => 'nullable|string',
                'bank_account_number' => 'nullable|string',
                'bank_name' => 'nullable|string',

            ]);
        }

        elseif ($this->currentStep === 2) {
            $rules = [
                'application_type' => 'required|in:accreditation,reaccreditation',
                'bankbook_photo' => 'required|image|max:5120',
                'cbl' => 'required|file|mimes:pdf|max:10240',
                'recent_fliers' => 'required|file|mimes:pdf,jpg,png|max:5120',
            ];

            if ($this->application_type === 'reaccreditation') {
                $rules['accomplishment_report'] = 'required|file|mimes:pdf|max:10240';
                $rules['audited_financial_report'] = 'required|file|mimes:pdf|max:10240';
            }
            $this->validate($rules);
        }

        elseif ($this->currentStep === 3) {
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
        }
        // Step 5 validates on final submit
    }

    // --- DYNAMIC ARRAY MANAGERS ---

    public function addOfficer() {
        $this->officers[] = ['position' => '', 'complete_name' => '', 'course_and_year' => '', 'college_id' => '', 'contact_number' => '', 'email_address' => '', 'home_address' => ''];
    }
    public function removeOfficer($index) { unset($this->officers[$index]); $this->officers = array_values($this->officers); }

    public function addMember() {
        $this->members[] = ['complete_name' => '', 'course_and_year' => '', 'college_id' => '', 'contact_number' => '', 'home_address' => ''];
    }
    public function removeMember($index) { unset($this->members[$index]); $this->members = array_values($this->members); }

    public function addActivity() {
        $this->activities[] = ['title' => '', 'description' => '', 'target_month' => ''];
    }
    public function removeActivity($index) { unset($this->activities[$index]); $this->activities = array_values($this->activities); }

    // --- FINAL SUBMISSION ---

    public function submit()
    {


        // Final validation for Step 5
        $this->validate([
            'activities.*.title' => 'required|string',
            'activities.*.description' => 'required|string',
        ]);

        // DB Transaction ensures if something fails, nothing saves
        DB::transaction(function () {
            // 1. Create or Update Organization
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

            // 2. Upload Files
            $bankbookPath = $this->bankbook_photo->store('accreditation/bankbooks', 'public');
            $cblPath = $this->cbl->store('accreditation/cbl', 'public');
            $fliersPath = $this->recent_fliers->store('accreditation/fliers', 'public');
            $presidentSigPath = $this->president_signature->store('accreditation/signatures', 'public');
            $adviserSigPath = $this->adviser_signature->store('accreditation/signatures', 'public');

            $accompPath = $this->application_type === 'reaccreditation' ? $this->accomplishment_report->store('accreditation/accomplishments', 'public') : null;
            $auditPath = $this->application_type === 'reaccreditation' ? $this->audited_financial_report->store('accreditation/audits', 'public') : null;

            // 3. Create Application Record
            $application = AccreditationApplication::create([
                'organization_id' => $org->id,
                'academic_year_id' => $this->academic_year_id,
                'application_type' => $this->application_type,
                'status' => 'pending',
                'bank_account_name' => $this->bank_account_name,
                'bank_account_number' => $this->bank_account_number,
                'bank_name' => $this->bank_name,
                'bankbook_photo_path' => $bankbookPath,
                'cbl_path' => $cblPath,
                'recent_fliers_path' => $fliersPath,
                'accomplishment_report_path' => $accompPath,
                'audited_financial_report_path' => $auditPath,
            ]);

            // 4. Save Dynamic Roster & Activities
            $application->officers()->createMany($this->officers);
            $application->members()->createMany($this->members);
            $application->activities()->createMany($this->activities);
        });

        session()->flash('success', 'Your application has been submitted and is now pending review.');
        return redirect()->route('accreditation.dashboard'); // Redirect to a success page
    }

    public function render()
    {
        return view('livewire.accreditation-wizard', [
            'academicYears' => AcademicYear::orderBy('id', 'desc')->get(),
            'colleges' => College::orderBy('name')->get(),
        ]);
    }
}
