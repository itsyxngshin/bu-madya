<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Campaign;
use App\Models\CampaignSignature;
use Illuminate\Support\Facades\DB;

class CampaignAnalytics extends Component
{
    public Campaign $campaign;

    // Analytics Data
    public $totalSignatures = 0;
    public $affiliationData = [];
    public $collegeData = [];
    public $yearLevelData = [];

    public function mount($slug)
    {
        // 1. Fetch Campaign & Security Check
        $this->campaign = Campaign::where('slug', $slug)->firstOrFail();
        
        $role = auth()->user()->role?->role_name;
        if (!in_array($role, ['administrator', 'director', 'organization'])) {
             abort(403, 'Unauthorized access.');
        }

        if ($role !== 'administrator' && $this->campaign->created_by !== auth()->id()) {
            abort(403, 'You do not own this campaign.');
        }

        // 2. Crunch the Numbers!
        $this->totalSignatures = $this->campaign->signatures()->count();

        // Group by Affiliation (Student, Alumni, etc.)
        $this->affiliationData = CampaignSignature::where('campaign_id', $this->campaign->id)
            ->select('affiliation', DB::raw('count(*) as total'))
            ->groupBy('affiliation')
            ->pluck('total', 'affiliation')
            ->toArray();

        // Group by College (Joining the colleges table to get the actual names!)
        $this->collegeData = CampaignSignature::where('campaign_id', $this->campaign->id)
            ->whereNotNull('college_id')
            ->join('colleges', 'campaign_signatures.college_id', '=', 'colleges.id')
            ->select('colleges.name', DB::raw('count(*) as total'))
            ->groupBy('colleges.id', 'colleges.name')
            ->orderByDesc('total')
            ->pluck('total', 'name')
            ->toArray();

        // Group by Year Level
        $this->yearLevelData = CampaignSignature::where('campaign_id', $this->campaign->id)
            ->whereNotNull('year_level')
            ->select('year_level', DB::raw('count(*) as total'))
            ->groupBy('year_level')
            ->orderBy('year_level')
            ->pluck('total', 'year_level')
            ->toArray();
    }

    public function render()
    {
        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.admin.campaign-analytics')->layout($layoutFile);
    }
}