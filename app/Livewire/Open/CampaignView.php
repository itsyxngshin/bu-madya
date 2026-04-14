<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Campaign;
use App\Models\CampaignSignature;
use App\Models\College; 
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]

class CampaignView extends Component
{
    public Campaign $campaign;
    public $colleges = []; // Holds the database data
    
    public $signatureCount = 0;
    public $hasSigned = false;

    // Guest & Demographic Fields
    public $guestName = '';
    public $guestEmail = '';
    public $affiliation = 'student';
    public $college_id = ''; // Changed from $college
    public $program = '';
    public $yearLevel = '';

    public function mount($slug)
    {
        $this->campaign = Campaign::with('creator')->where('slug', $slug)->firstOrFail();
        $this->signatureCount = $this->campaign->signatures()->count();

        // Fetch all colleges from your database to populate the dropdown
        $this->colleges = College::orderBy('name')->get();

        if (auth()->check()) {
            $this->hasSigned = $this->campaign->signatures()->where('user_id', auth()->id())->exists();
        }
    }

    public function signPetition()
    {
        if ($this->hasSigned) return;

        $rules = [
            'affiliation' => 'required|in:student,alumni,stakeholder,faculty',
        ];

        if (!auth()->check()) {
            $rules['guestName'] = 'required|string|max:255';
            $rules['guestEmail'] = 'required|email|max:255';
        }

        if ($this->affiliation === 'student') {
            $rules['college_id'] = 'required|exists:colleges,id'; // Validates against the DB
            $rules['program'] = 'required|string|max:255';
            $rules['yearLevel'] = 'required|string|max:50';
        }

        $this->validate($rules, [
            'college_id.required' => 'Please select your college.',
            'program.required' => 'Please enter your program/course.',
            'yearLevel.required' => 'Please select your year level.',
        ]);

        if (!auth()->check()) {
            $alreadySigned = CampaignSignature::where('campaign_id', $this->campaign->id)
                ->where('guest_email', $this->guestEmail)
                ->exists();

            if ($alreadySigned) {
                $this->addError('guestEmail', 'This email has already signed the petition.');
                return;
            }
        }

        CampaignSignature::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => auth()->check() ? auth()->id() : null,
            'guest_name' => auth()->check() ? null : $this->guestName,
            'guest_email' => auth()->check() ? null : $this->guestEmail,
            'affiliation' => $this->affiliation,
            'college_id' => $this->affiliation === 'student' ? $this->college_id : null,
            'program' => $this->affiliation === 'student' ? $this->program : null,
            'year_level' => $this->affiliation === 'student' ? $this->yearLevel : null,
        ]);

        $this->signatureCount++;
        $this->hasSigned = true;
        $this->dispatch('petition-signed'); 
    }

    public function render()
    {
        $progress = $this->campaign->target_signatures > 0 
            ? min(100, ($this->signatureCount / $this->campaign->target_signatures) * 100) 
            : 0;

        return view('livewire.open.campaign-view', [
            'progressPercentage' => $progress
        ]);
    }
}