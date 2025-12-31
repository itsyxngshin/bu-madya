<?php

// app/Livewire/MembershipForm.php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\MembershipApplication;
use App\Models\College; 
use App\Models\Committee;
use App\Models\MembershipWave;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class RegistrationForm extends Component
{ 
    use WithFileUploads;

    // Steps for a multi-step form feel (Optional, but good for long forms)
    // For now, we will do a single long page for simplicity as per Google Form style.

    // Properties
    public $privacy_consent = false;
    
    public $last_name, $first_name, $middle_initial;
    public $home_address, $birthday, $contact_number, $email, $facebook_link;
    public $year_level, $course;
    
    public $political_affiliation;
    public $photo, $signature; // Temporary file uploads
    public $college_id;        // Was $college
    public $committee_1_id;    // Was $committee_1
    public $committee_2_id;    // Was $committee_2
    
    public $essay_1_community, $essay_2_action, $essay_3_experience;
    public $essay_4_reason, $essay_5_suggestion;
    public $activeWave;
    
    public $willing_to_pay = false;
    protected $rules = [
        'privacy_consent' => 'accepted',
        'last_name' => 'required|string|min:2',
        'first_name' => 'required|string|min:2',
        'home_address' => 'required|string',
        'birthday' => 'required|date',
        'contact_number' => 'required|string',
        'email' => 'required|email',
        'college_id' => 'required|exists:colleges,id',
        'committee_1_id' => 'required|exists:committees,id',
        'committee_2_id' => 'required|exists:committees,id|different:committee_1_id',
        'course' => 'required',
        'year_level' => 'required',
        'photo' => 'required|image|max:5120', // 5MB Max
        'signature' => 'required|image|max:5120',
        'political_affiliation' => 'nullable|string',
        'essay_1_community' => 'required|string|min:20',
        'essay_2_action' => 'required|string|min:20',
        'essay_4_reason' => 'required|string|min:20',
        'willing_to_pay' => 'accepted', // Must say YES to join
    ];

    public function mount()
    {
        // 1. Find the currently active wave
        $this->activeWave = MembershipWave::where('is_active', true)
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now())
                            ->first();
    }

    public function submit()
    {
        if (!$this->activeWave) {
            session()->flash('error', 'Registration is currently closed.');
            return;
        }

        $this->validate();

        // Save Files
        $photoPath = $this->photo->store('applications/photos', 'public');
        $sigPath = $this->signature->store('applications/signatures');

        MembershipApplication::create([
            'privacy_consent' => true,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'middle_initial' => $this->middle_initial,
            'home_address' => $this->home_address,
            'birthday' => $this->birthday,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'facebook_link' => $this->facebook_link,
            'college_id' => $this->college_id,
            'committee_1_id' => $this->committee_1_id,
            'committee_2_id' => $this->committee_2_id,
            'year_level' => $this->year_level,
            'course' => $this->course,
            'political_affiliation' => $this->political_affiliation,
            'photo_path' => $photoPath,
            'membership_wave_id' => $this->activeWave->id,
            'signature_path' => $sigPath,
            'essay_1_community' => $this->essay_1_community,
            'essay_2_action' => $this->essay_2_action,
            'essay_3_experience' => $this->essay_3_experience,
            'essay_4_reason' => $this->essay_4_reason,
            'essay_5_suggestion' => $this->essay_5_suggestion,
            'willing_to_pay' => true,
        ]);

        session()->flash('message', 'Application submitted successfully! Please wait for our email regarding the next steps.');
        $this->reset(); // Clear form
    }

    public function render()
    {
        $dbColleges = College::orderBy('name')->get();
        $dbCommittees = Committee::orderBy('name')->get();

        return view('livewire.registration-form', [
            'colleges' => $dbColleges,
            'committees' => $dbCommittees,
        ]);
    }
}