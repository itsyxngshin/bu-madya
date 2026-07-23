<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\IbalongRegistration;
use App\Models\IbalongSetting; // <-- Add this import
use App\Mail\IbalongRegistrationReceived;

class RegistrationForm extends Component
{
    public $isRegistrationOpen;

    public $step = 1;
    public $registrationSuccessful = false;

    // Form Data
    public $team_name, $team_about, $affiliation;
    public $provCode = '';
    public $citymunCode = '';
    public $brgyCode = '';

    public $provinces = [];
    public $cities = [];
    public $barangays = [];

    public $team_skills = [];
    public $team_community_areas = [];
    public $team_experiences = [];

    public $members = [];

    public $team_online_activities = [];
    public $team_member_demographics = '';
    public $onsite_commitment = '';
    public $does_not_automatically_apply_clause = '';
    public $selection_on_icp = '';
    public $media_consent = false;
    public $data_privacy_consent = false;

    public $ref_skills, $ref_experiences, $ref_community_areas, $ref_online_activities;

    public function mount()
    {
        // 1. Fetch live status from the database
        $setting = IbalongSetting::firstOrCreate(['id' => 1]);
        $this->isRegistrationOpen = $setting->is_registration_open;

        // 2. If locked, stop processing and render the lockdown screen immediately
        if (!$this->isRegistrationOpen) {
            return;
        }

        $this->members = [
            $this->getEmptyMemberTemplate('Team Leader')
        ];

        // Load reference dictionaries
        $this->ref_skills = DB::table('ibalong_skills')->get();
        $this->ref_experiences = DB::table('ibalong_experiences')->get();
        $this->ref_community_areas = DB::table('ibalong_community_areas')->get();
        $this->ref_online_activities = DB::table('ibalong_online_activities')->get();

        // Initialize PSGC Location Data (Default to Region V / 05)
        $this->provinces = DB::table('refprovince')->where('regCode', '05')->orderBy('provDesc')->get();
    }

    // --- CASCADING DROPDOWN HOOKS ---
    public function updatedProvCode($value)
    {
        $this->cities = DB::table('refcitymun')->where('provCode', $value)->orderBy('citymunDesc')->get();
        $this->citymunCode = '';
        $this->brgyCode = '';
        $this->barangays = [];
    }

    public function updatedCitymunCode($value)
    {
        $this->barangays = DB::table('refbrgy')->where('citymunCode', $value)->orderBy('brgyDesc')->get();
        $this->brgyCode = '';
    }

    private function getEmptyMemberTemplate($role = 'Team Member')
    {
        return [
            'full_name' => '', 'email_address' => '', 'mobile_number' => '',
            'birthday' => '', 'course' => '', 'role' => '', 'position' => '',
            'affiliation' => '', 'team_role' => $role, 'skills' => []
        ];
    }

    public function addMember()
    {
        if (count($this->members) < 5) {
            $this->members[] = $this->getEmptyMemberTemplate();
        }
    }

    public function removeMember($index)
    {
        if (count($this->members) > 1) {
            unset($this->members[$index]);
            $this->members = array_values($this->members);
        }
    }

    public function nextStep()
    {
        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function submit()
    {
        // Double-check security on submission in case they bypassed the UI
        if (!$this->isRegistrationOpen) {
            return;
        }

        $this->validate([
            'team_name' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
            'team_about' => ['bail', 'required', 'string', function ($attribute, $value, $fail) {
                if (str_word_count((string) $value) > 250) {
                    $fail('The cohort manifesto must not exceed 250 words.');
                }
            }],
            'team_member_demographics' => 'required',
            'onsite_commitment' => 'required',
            'data_privacy_consent' => 'accepted',
            'media_consent' => 'accepted',
            'provCode' => 'required',
            'citymunCode' => 'required',
            'brgyCode' => 'required',
        ]);

        $registration = DB::transaction(function () {
            $teamSlug = Str::slug($this->team_name) . '-' . strtolower(Str::random(5));

            $reg = IbalongRegistration::create([
                'team_name' => $this->team_name,
                'slug' => $teamSlug,
                'team_about' => $this->team_about,
                'affiliation' => $this->affiliation,
                'province_id' => $this->provCode,
                'citymun_id' => $this->citymunCode,
                'barangay_id' => $this->brgyCode,
                'team_member_demographics' => $this->team_member_demographics,
                'number_of_team_members' => count($this->members),
                'onsite_commitment' => $this->onsite_commitment,
                'does_not_automatically_apply_clause' => $this->does_not_automatically_apply_clause,
                'selection_on_icp' => $this->selection_on_icp,
                'media_consent' => $this->media_consent,
                'data_privacy_consent' => $this->data_privacy_consent,
                'status' => 'pending'
            ]);

            $reg->skills()->sync($this->team_skills);
            $reg->communityAreas()->sync($this->team_community_areas);
            $reg->experiences()->sync($this->team_experiences);
            $reg->onlineActivities()->sync($this->team_online_activities);

            foreach ($this->members as $memberData) {
                $memberSlug = Str::slug($memberData['full_name']) . '-' . strtolower(Str::random(5));

                $member = $reg->members()->create([
                    'full_name' => $memberData['full_name'],
                    'slug' => $memberSlug,
                    'email_address' => $memberData['email_address'],
                    'mobile_number' => $memberData['mobile_number'],
                    'birthday' => $memberData['birthday'] ?: null,
                    'course' => $memberData['course'],
                    'role' => $memberData['role'],
                    'position' => $memberData['position'],
                    'affiliation' => $memberData['affiliation'] ?? $this->affiliation,
                    'team_role' => $memberData['team_role'],
                ]);

                if (!empty($memberData['skills'])) {
                    $member->skills()->sync($memberData['skills']);
                }
            }

            return $reg;
        });

        $teamLeader = collect($this->members)->where('team_role', 'Team Leader')->first() ?? $this->members[0];

        try {
            Mail::to($teamLeader['email_address'])->send(new IbalongRegistrationReceived($registration, $teamLeader));
        } catch (\Exception $e) {
            \Log::error('Registration Mail Failed: ' . $e->getMessage());
        }

        $this->registrationSuccessful = true;
    }

    public function render()
    {
        return view('livewire.ibalong.registration-form')->layout('layouts.ibalong-layout');
    }
}
