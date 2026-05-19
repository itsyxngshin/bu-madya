<?php

namespace App\Livewire\Partner;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use App\Models\AccreditationDeadline;
use App\Models\Advisory;

class AccreditationDashboard extends Component
{
    public function render()
    {
        // 1. Get the logged-in user's organization profile
        $organization = Organization::where('user_id', Auth::id())->first();
        
        // 2. Get their most recent application (if they have one)
        $latestApplication = null;
        if ($organization) {
            $latestApplication = $organization->applications()
                ->with('academicYear')
                ->latest()
                ->first();
        }

        // 3. Fetch OSAS global data
        $activeDeadlines = AccreditationDeadline::with('academicYear')
            ->where('is_active', true)
            ->orderBy('end_date', 'asc')
            ->get();
            
        $advisories = Advisory::where('is_published', true)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.partner.accreditation-dashboard', [
            'organization' => $organization,
            'application' => $latestApplication,
            'activeDeadlines' => $activeDeadlines,
            'advisories' => $advisories,
        ]);
    }
} 