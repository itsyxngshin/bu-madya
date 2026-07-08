<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IbalongCommittee;
use App\Models\IbalongCommitteeMember;

class VolunteerIntake extends Component
{
    use WithFileUploads;

    public $committee_id, $name, $email, $mobile_number, $affiliation, $motivation, $photo;
    public $devcon_consent = false;
    public $isSubmitted = false;

    protected $rules = [
        'committee_id' => 'required|exists:ibalong_committees,id',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'mobile_number' => 'required|string|max:20',
        'affiliation' => 'nullable|string|max:255',
        'motivation' => 'required|string|max:1000',
        'photo' => 'nullable|image|max:2048', // 2MB Max
        'devcon_consent' => 'accepted', // Enforces that the checkbox must be ticked
    ];

    public function submit()
    {
        $this->validate();

        $path = $this->photo ? $this->photo->store('committees', 'public') : null;

        IbalongCommitteeMember::create([
            'committee_id' => $this->committee_id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'affiliation' => $this->affiliation,
            'designation' => 'Volunteer', 
            'motivation' => $this->motivation,
            'devcon_consent' => true, // Save the boolean state
            'role' => 'Member', 
            'photo_path' => $path,
            'display_order' => 99, // Push new volunteers to the bottom of the list
            'is_active' => false, // Hidden by default for admin approval
        ]);

        $this->isSubmitted = true;
    }

    public function render()
    {
        $committees = IbalongCommittee::where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get();

        return view('livewire.ibalong.volunteer-intake', [
            'committees' => $committees,
        ])->layout('layouts.ibalong-layout');
    }
}