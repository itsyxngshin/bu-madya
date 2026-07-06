<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\IbalongRegistration; 

class RegistrationForm extends Component
{
    public $step = 1;

    // Form Data - Step 1
    public $team_name, $team_about, $affiliation;
    public $province_id = 1, $citymun_id = 1, $barangay_id = 1; // Fallbacks for PSGC
    public $team_community_areas = [];
    public $team_experiences = [];

    // Form Data - Step 2 (Members)
    public $members = [];

    // Form Data - Step 3 (Verification & Consents)
    public $team_member_demographics = '';
    public $onsite_commitment = '';
    public $does_not_automatically_apply_clause = '';
    public $selection_on_icp = '';
    public $media_consent = false;
    public $data_privacy_consent = false;

    // Reference Data (Loaded for the UI)
    public $ref_skills, $ref_experiences, $ref_community_areas;

    public function mount()
    {
        // Initialize with 1 Team Leader
        $this->members = [
            $this->getEmptyMemberTemplate('Team Leader')
        ];

        // Load reference dictionaries
        $this->ref_skills = DB::table('ibalong_skills')->get();
        $this->ref_experiences = DB::table('ibalong_experiences')->get();
        $this->ref_community_areas = DB::table('ibalong_community_areas')->get();
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
        if ($this->step === 1) {
            $this->validate([
                'team_name' => 'required|string|max:255',
                'team_about' => 'required|string',
                'team_community_areas' => 'required|array|min:1',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'members' => 'required|array|min:3|max:5',
                'members.*.full_name' => 'required|string',
                'members.*.email_address' => 'required|email',
            ], [
                'members.min' => 'You must assemble a cohort of at least 3 members.',
                'members.max' => 'Maximum cohort capacity is 5 members.',
            ]);
        }
        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function submit()
    {
        // Final strict verification
        $this->validate([
            'team_member_demographics' => 'required',
            'onsite_commitment' => 'required',
            'data_privacy_consent' => 'accepted',
            'media_consent' => 'accepted',
        ]);

        DB::transaction(function () {
            // Generate Unique Slug
            $teamSlug = Str::slug($this->team_name) . '-' . strtolower(Str::random(5));
            
            // 1. Create Core Registration
            $registration = IbalongRegistration::create([
                'team_name' => $this->team_name,
                'slug' => $teamSlug,
                'team_about' => $this->team_about,
                'affiliation' => $this->affiliation,
                'province_id' => $this->province_id,
                'citymun_id' => $this->citymun_id,
                'barangay_id' => $this->barangay_id,
                'team_member_demographics' => $this->team_member_demographics,
                'number_of_team_members' => count($this->members),
                'onsite_commitment' => $this->onsite_commitment,
                'does_not_automatically_apply_clause' => $this->does_not_automatically_apply_clause,
                'selection_on_icp' => $this->selection_on_icp,
                'media_consent' => $this->media_consent,
                'data_privacy_consent' => $this->data_privacy_consent,
                'status' => 'pending'
            ]);

            // 2. Sync Team Pivots
            $registration->communityAreas()->sync($this->team_community_areas);
            $registration->experiences()->sync($this->team_experiences);

            // 3. Create Members and Sync Member Pivots
            foreach ($this->members as $memberData) {
                $memberSlug = Str::slug($memberData['full_name']) . '-' . strtolower(Str::random(5));
                
                $member = $registration->members()->create([
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
        });

        // Use standard redirect (update to match your actual success route name)
        return redirect('/')->with('success', 'Cohort initialized successfully!');
    }

    public function render()
    {
        return view('livewire.ibalong.registration-form')->layout('layouts.ibalong-layout');
    }
}