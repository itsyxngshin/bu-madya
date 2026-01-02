<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithPagination; // Optional, if you expect many partners
use App\Models\Linkage;
use App\Models\LinkageType;
use App\Models\LinkageActivity;
use App\Models\AgreementLevel;
use Livewire\Attributes\Layout; 
use App\Models\SiteStat;
use Illuminate\Support\Facades\Session;

#[Layout('layouts.madya-template')]
class LinkagesIndex extends Component
{
    use WithPagination;

    public $category = 'All';
    public $visitorCount = 1;
    public function mount()
    {
        // 1. Check if this specific user has already been counted in this session
        if (!Session::has('has_visited_site')) {
            
            // 2. Increment the database value securely
            SiteStat::where('key', 'visitor_count')->increment('value');
            
            // 3. Mark this user as counted for this browser session
            Session::put('has_visited_site', true);
        }

        // 4. Retrieve the current total (cache it briefly to reduce DB queries on high traffic)
        // We remember it for 10 minutes, or fetch directly if you want instant real-time
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
    }

    // 1. Filter Action
    public function setCategory($categoryName)
    {
        $this->category = $categoryName;
        $this->resetPage(); // Reset pagination if used
    }

    // 2. Computed Property: Linkage Types (for filter buttons)
    public function getTypesProperty()
    {
        return LinkageType::orderBy('name')->get();
    }

    // 3. Computed Property: Partners List
    public function getPartnersProperty()
    {
        return Linkage::query()
            ->with(['type', 'status']) // Eager load relationships
            ->when($this->category !== 'All', function ($query) {
                $query->whereHas('type', function ($q) {
                    $q->where('name', $this->category);
                });
            })
            ->orderBy('name')
            ->get();
    }

    // 4. Computed Property: Recent Engagements (Timeline)
    public function getEngagementsProperty()
    {
        return LinkageActivity::with('linkage')
            ->latest('activity_date')
            ->take(5)
            ->get();
    }

    // 5. Computed Property: Stats
    public function getStatsProperty()
    {
        return [
            'active_count' => Linkage::whereHas('status', fn($q) => $q->where('slug', 'active'))->count(),
            'moa_count'    => Linkage::whereHas('agreementLevel', fn($q) => $q->where('slug', 'moa'))->count(),
            'intl_count'   => Linkage::whereHas('type', fn($q) => $q->where('slug', 'international'))->count(), // Adjust 'international' based on your actual data
        ];
    }

    public function render()
    {
        return view('livewire.director.linkages-index');
    }
}