<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Campaign;
use App\Models\CampaignSignature;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class CampaignView extends Component
{
    public Campaign $campaign;
    public $signatureCount = 0;
    public $hasSigned = false;

    public function mount($slug)
    {
        $this->campaign = Campaign::with('creator')->where('slug', $slug)->firstOrFail();
        $this->signatureCount = $this->campaign->signatures()->count();

        // Check if the currently logged-in user already signed
        if (auth()->check()) {
            $this->hasSigned = $this->campaign->signatures()->where('user_id', auth()->id())->exists();
        }
    }

    public function signPetition()
    {
        // 1. Must be logged in to sign (prevents fake spam signatures)
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to sign this petition.');
        }

        // 2. Double check they haven't signed already
        if ($this->hasSigned) {
            return;
        }

        // 3. Record the signature
        CampaignSignature::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => auth()->id(),
        ]);

        // 4. Update UI state instantly
        $this->signatureCount++;
        $this->hasSigned = true;

        // Optional: Dispatch an event to trigger a confetti animation on the frontend!
        $this->dispatch('petition-signed'); 
    }

    public function render()
    {
        // Calculate progress percentage (capped at 100% visually)
        $progress = $this->campaign->target_signatures > 0 
            ? min(100, ($this->signatureCount / $this->campaign->target_signatures) * 100) 
            : 0;

        return view('livewire.open.campaign-view', [
            'progressPercentage' => $progress
        ]);
    }
}
