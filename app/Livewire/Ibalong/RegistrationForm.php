<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\IbalongRegistration; 

class RegistrationForm extends Component
{
    public $step = 1;

    // Form Data - Step 1 (Cohort Profile)
    public $team_name, $team_about, $affiliation;
    
    // PSGC Geographic Data
    public $provCode = '';
    public $citymunCode = '';
    public $brgyCode = '';
    
    // Cascading Dropdown Options
    public $provinces = [];
    public $cities = [];
    public $barangays = [];

    // Pivot Arrays
    public $team_skills = [];
    public $team_community_areas = [];
    public $team_experiences = [];

    // Form Data - Step 2 (Members)
    public $members = [];

    // Form Data - Step 3 (Verification & Logistics)
    public $team_online_activities = [];
    public $team_member_demographics = '';
    public $onsite_commitment = '';
    public $does_not_automatically_apply_clause = '';
    public $selection_on_icp = '';
    public $media_consent = false;
    public $data_privacy_consent = false;

    // Reference Data (Loaded for the UI)
    public $ref_skills, $ref_experiences, $ref_community_areas, $ref_online_activities;

    public function mount()
    {
        $this->members = [
            $this->getEmptyMemberTemplate('Team Leader')
        ];

        // Load reference dictionaries
        $this->ref_skills = DB::table('ibalong_skills')->get();
        $this->ref_experiences = DB::table('ibalong_experiences')->get();
        $this->ref_community_areas = DB::table('ibalong_community_areas')->get();
        $this->ref_online_activities = DB::table('ibalong_online_activities')->get();

        // Initialize PSGC Location Data (Default to Region V / 05)
        // Fetches provinces mapped to regCode '05'
        $this->provinces = DB::table('refprovince')->where('regCode', '05')->orderBy('provDesc')->get();
    }

    // --- CASCADING DROPDOWN HOOKS ---

    public function updatedProvCode($value)
    {
        // When Province changes, fetch related Cities/Municipalities
        $this->cities = DB::table('refcitymun')->where('provCode', $value)->orderBy('citymunDesc')->get();
        
        // Reset downstream selections
        $this->citymunCode = '';
        $this->brgyCode = '';
        $this->barangays = [];
    }

    public function updatedCitymunCode($value)
    {
        // When City/Municipality changes, fetch related Barangays
        $this->barangays = DB::table('refbrgy')->where('citymunCode', $value)->orderBy('brgyDesc')->get();
        
        // Reset downstream selection
        $this->brgyCode = '';
    }

    // --- END HOOKS ---

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
        $this->validate([
            'team_member_demographics' => 'required',
            'onsite_commitment' => 'required',
            'data_privacy_consent' => 'accepted',
            'media_consent' => 'accepted',
            'provCode' => 'required',
            'citymunCode' => 'required',
            'brgyCode' => 'required',
        ]);

        DB::transaction(function () {
            $teamSlug = Str::slug($this->team_name) . '-' . strtolower(Str::random(5));
            
            // 1. Create Core Registration
            $registration = IbalongRegistration::create([
                'team_name' => $this->team_name,
                'slug' => $teamSlug,
                'team_about' => $this->team_about,
                'affiliation' => $this->affiliation,
                'provCode' => $this->provCode,
                'citymunCode' => $this->citymunCode,
                'brgyCode' => $this->brgyCode,
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
            $registration->skills()->sync($this->team_skills);
            $registration->communityAreas()->sync($this->team_community_areas);
            $registration->experiences()->sync($this->team_experiences);
            $registration->onlineActivities()->sync($this->team_online_activities);

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

        return redirect('/')->with('success', 'Cohort initialized successfully!');
    }

    public function render()
    {
        return view('livewire.ibalong.registration-form')->layout('layouts.ibalong-layout');
    }
}